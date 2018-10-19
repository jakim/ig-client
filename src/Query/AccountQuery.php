<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 14.03.2018
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use Jakim\Helper\JsonHelper;
use jakim\ig\Endpoint;
use jakim\ig\Url;
use Jakim\Mapper\AccountDetails;
use Jakim\Mapper\AccountInfo;
use Jakim\Mapper\AccountMedia;
use Jakim\Model\Account;
use Jakim\Model\Post;

class AccountQuery extends Query
{
    protected $accountDetailsMapper;
    protected $accountMediaMapper;
    protected $accountInfoMapper;

    public function __construct($httpClient, AccountDetails $accountDetailsMapper = null, AccountMedia $accountMediaMapper = null, AccountInfo $accountInfoMapper = null)
    {
        parent::__construct($httpClient);
        $this->accountDetailsMapper = $accountDetailsMapper ?? new AccountDetails();
        $this->accountMediaMapper = $accountMediaMapper ?? new AccountMedia();
        $this->accountInfoMapper = $accountInfoMapper ?? new AccountInfo();
    }

    /**
     * @param mixed $ident username or account id
     * @return \Jakim\Model\Account
     */
    public function findOne($ident): Account
    {
        if (is_numeric($ident)) {
            return $this->findOneById($ident);
        }

        return $this->findOneByUsername($ident);
    }

    public function findOneByUsername(string $username)
    {
        $url = Url::account($username);
        $data = $this->fetchContentAsArray($url);

        $this->throwEmptyContentExceptionIfEmpty($data);

        $data = $this->accountDetailsMapper->normalizeData(Account::class, $data);

        return $this->accountDetailsMapper->populate(Account::class, $data);
    }

    public function findOneById($accountId)
    {
        $url = Endpoint::accountInfo($accountId);
        $data = parent::fetchContentAsArray($url);

        $this->throwEmptyContentExceptionIfEmpty($data);

        $data = $this->accountInfoMapper->normalizeData(Account::class, $data);

        return $this->accountInfoMapper->populate(Account::class, $data);
    }

    /**
     * @param string $username
     * @param int $limit Max 12, for more see findPosts()
     * @return \Generator
     *
     * @throws \Jakim\Exception\EmptyContentException
     * @see \Jakim\Query\AccountQuery::findPosts
     */
    public function findLastPosts(string $username, int $limit = 12)
    {
        $url = Url::account($username);
        $data = $this->fetchContentAsArray($url);

        $this->throwEmptyContentExceptionIfEmpty($data);

        $items = $this->accountDetailsMapper->normalizeData(Post::class, $data);

        $n = 0;
        foreach ($items as $item) {
            $model = $this->accountDetailsMapper->populate(Post::class, $item);
            print_r($model);
            yield $model;

            if (++$n >= $limit) {
                break;
            }
        }
    }

    /**
     * @param string $username
     * @param int $limit
     * @return \Generator
     *
     * @throws \Jakim\Exception\EmptyContentException
     * @deprecated
     */
    public function findPosts(string $username, int $limit = 100)
    {
        yield from $this->findLastPosts($username, $limit);
    }

    protected function fetchContentAsArray(string $url): ?array
    {
        $res = $this->httpClient->get($url);
        $content = $res->getBody()->getContents();

        preg_match('/\_sharedData \= (.*?)\;\<\/script\>/s', $content, $matches);

        return JsonHelper::decode($matches['1']);
    }
}