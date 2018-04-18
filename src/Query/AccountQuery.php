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
use Jakim\Mapper\AccountMedia;
use Jakim\Model\Account;
use Jakim\Model\Post;

class AccountQuery extends Query
{
    const MAX_POSTS_PER_PAGE = 100;
    public $postsPerPage = 100;

    protected $accountDetailsMapper;
    protected $accountMediaMapper;

    public function __construct($httpClient, AccountDetails $accountDetailsMapper = null, AccountMedia $accountMediaMapper = null)
    {
        parent::__construct($httpClient);
        $this->accountDetailsMapper = $accountDetailsMapper ?? new AccountDetails();
        $this->accountMediaMapper = $accountMediaMapper ?? new AccountMedia();
    }

    public function findOne(string $username): Account
    {
        $url = Url::account($username);
        $data = $this->fetchContentAsArray($url);

        $data = $this->accountDetailsMapper->normalizeData(Account::class, $data);

        return $this->accountDetailsMapper->populate(Account::class, $data);
    }

    protected function fetchContentAsArray(string $url): ?array
    {
        $res = $this->httpClient->get($url);
        $content = $res->getBody()->getContents();

        preg_match('/\_sharedData \= (.*?)\;\<\/script\>/', $content, $matches);

        return JsonHelper::decode($matches['1']);
    }

    /**
     * @param string $username
     * @param int $limit Max 12, for more see findPosts()
     * @return \Generator
     *
     * @see \Jakim\Query\AccountQuery::findPosts
     */
    public function findLastPosts(string $username, int $limit = 12)
    {
        $url = Endpoint::accountDetails($username);
        $data = $this->fetchContentAsArray($url);

        $items = $this->accountDetailsMapper->normalizeData(Post::class, $data);

        $n = 0;
        foreach ($items as $item) {
            $model = $this->accountDetailsMapper->populate(Post::class, $item);

            yield $model;

            if (++$n >= $limit) {
                break;
            }
        }
    }

    public function findPosts(string $username, int $limit = 100)
    {
        if ($limit <= 12) {
            yield from $this->findLastPosts($username, $limit);

            return;
        }

        $account = $this->findOne($username);

        $n = 0;
        $nextPage = '';
        $this->postsPerPage = (int)$this->postsPerPage > self::MAX_POSTS_PER_PAGE ? self::MAX_POSTS_PER_PAGE : $this->postsPerPage;

        while ($nextPage !== null) {
            $url = Endpoint::accountMedia($account->id, $this->postsPerPage, [
                'variables' => ['after' => $nextPage],
            ]);
            $data = $this->fetchContentAsArray($url);

            $nextPage = $this->accountMediaMapper->nextPage($data);

            $items = $this->accountMediaMapper->normalizeData(Post::class, $data);

            foreach ($items as $item) {

                yield $this->accountMediaMapper->populate(Post::class, $item);

                if (++$n >= $limit) {
                    break 2;
                }
            }
        }
    }
}