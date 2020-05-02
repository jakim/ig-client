<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 01/05/2020
 */

namespace Jakim;


use Jakim\Query\AccountQuery;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class IGClient
{
    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface|\Symfony\Component\HttpClient\CurlHttpClient
     */
    protected HttpClientInterface $httpClient;
    protected ?string $username;
    protected ?string $password;

    /**
     * IgClient constructor.
     *
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $httpClient
     * @param string|null $username
     * @param string|null $password
     */
    public function __construct(HttpClientInterface $httpClient, ?string $username = null, ?string $password = null)
    {
        $this->httpClient = $httpClient;
        $this->username = $username;
        $this->password = $password;
    }

    public function accountQuery(): AccountQuery
    {
        return new AccountQuery(clone $this);
    }

    public function login()
    {
        $new = clone $this;

        return $new;
    }

    public function get(string $url): ResponseInterface
    {
        return $this->httpClient
            ->request('GET', $url);
    }
}