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
use Jakim\Hydrator\ModelHydrator;
use jakim\ig\Endpoint;
use jakim\ig\Url;
use Jakim\Map\AccountDetails;
use Jakim\Map\AccountInfo;
use Jakim\Map\EdgeMedia;
use Jakim\Model\Account;
use Jakim\Model\MediaCollection;

class AccountQuery extends Query
{
    /**
     * @param string $username
     * @return \Jakim\Model\Account|object
     *
     * @throws \Jakim\Exception\LoginAndSignupPageException
     * @throws \Jakim\Exception\RestrictedProfileException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findOneByUsername(string $username): Account
    {
        $url = Url::account($username);
        $response = $this->IGClient->get($url);
        $content = $response->getContent();
        $content = $this->getSharedDataContent($content);

        $content = JsonHelper::decode($content);
        $hydrator = new ModelHydrator((new AccountDetails())->config());

        return $hydrator->hydrate(new Account(), $content);
    }

    /**
     * @param string $username
     * @return \Jakim\Model\MediaCollection|\Jakim\Model\ModelInterface
     *
     * @throws \Jakim\Exception\LoginAndSignupPageException
     * @throws \Jakim\Exception\RestrictedProfileException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findLatestMedia(string $username): MediaCollection
    {
        $url = Url::account($username);
        $response = $this->IGClient->get($url);
        $content = $response->getContent();
        $content = $this->getSharedDataContent($content);

        $content = JsonHelper::decode($content);
        $hydrator = new ModelHydrator((new EdgeMedia(EdgeMedia::ACCOUNT_DETAILS_ENVELOPE))->config());

        return $hydrator->hydrate(new MediaCollection(), $content);
    }

    /**
     * @param $accountId
     * @return \Jakim\Model\Account|\Jakim\Model\ModelInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findOneById($accountId): Account
    {
        $url = Endpoint::accountInfo($accountId);
        $response = $this->IGClient->get($url);
        $content = $response->getContent();

        $content = JsonHelper::decode($content);
        $hydrator = new ModelHydrator((new AccountInfo())->config());

        return $hydrator->hydrate(new Account(), $content);
    }

    protected function getSharedDataContent(string $content): string
    {
        preg_match('/\_sharedData \= (.*?)\;\<\/script\>/s', $content, $matches);

        if (isset($matches['1']) && strpos($matches['1'], 'LoginAndSignupPage') !== false) {
            throw new LoginAndSignupPageException();
        }

        if (empty($matches) && strpos($content, 'Restricted profile') !== false) {
            throw new RestrictedProfileException();
        }

        return $matches['1'];
    }
}