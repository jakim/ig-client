<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 14.03.2018
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use Jakim\Exception\LoginAndSignupPageException;
use Jakim\Exception\RestrictedProfileException;
use Jakim\Helper\JsonHelper;
use jakim\ig\Endpoint;
use jakim\ig\Url;
use Jakim\Mapper\AccountDetails;
use Jakim\Mapper\AccountInfo;
use Jakim\Mapper\EdgeMedia;
use Jakim\Model\Account;
use Jakim\Model\MediaCollection;

class AccountQuery extends Query
{
    /**
     * @var \Jakim\Base\Mapper|\Jakim\Mapper\AccountDetails
     */
    protected $findOneByUsername;

    /**
     * @var \Jakim\Base\Mapper|\Jakim\Mapper\AccountInfo
     */
    protected $findOneByOne;

    /**
     * @var \Jakim\Base\Mapper|\Jakim\Mapper\EdgeMedia
     */
    protected $findLatestMedia;

    public function __construct(
        $httpClient,
        AccountDetails $findOneByUsername = null,
        AccountInfo $findOneById = null,
        EdgeMedia $findLatestMedia = null
    )
    {
        parent::__construct($httpClient);
        $this->findOneByUsername = $findOneByUsername;
        $this->findOneByOne = $findOneById;
        $this->findLatestMedia = $findLatestMedia;
    }

    public function findOneByUsername(string $username): Account
    {
        $url = Url::account($username);

        return $this->createResult($url, $this->findOneByUsername, false);
    }

    public function findOneById($accountId): Account
    {
        $url = Endpoint::accountInfo($accountId);
        $content = parent::fetchContentAsArray($url); // from api, not sharedData :)

        $this->throwEmptyContentExceptionIfEmpty($content);

        $config = $this->findOneByOne->config();
        $data = $this->findOneByOne->getData($content, $config);

        return $this->findOneByOne->createModel($data, $config);
    }

    public function findLatestMedia(string $username, bool $relations = false): MediaCollection
    {
        $url = Url::account($username);

        return $this->createResult($url, $this->findLatestMedia, $relations);
    }

    protected function fetchContentAsArray(string $url): ?array
    {
        $res = $this->httpClient->get($url);
        $content = $res->getBody()->getContents();

        preg_match('/\_sharedData \= (.*?)\;\<\/script\>/s', $content, $matches);

        if (isset($matches['1']) && strpos($matches['1'], 'LoginAndSignupPage') !== false) {
            throw new LoginAndSignupPageException();
        }

        if (empty($matches) && strpos($content, 'Restricted profile') !== false) {
            throw new RestrictedProfileException();
        }

        return JsonHelper::decode($matches['1']);
    }
}