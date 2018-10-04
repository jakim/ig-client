<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Base;


use Jakim\Exception\EmptyContentException;
use Jakim\Helper\JsonHelper;

abstract class Query
{
    /**
     * Psr7 compatible client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    protected function fetchContentAsArray(string $url): ?array
    {
        $res = $this->httpClient->get($url);
        $content = $res->getBody()->getContents();

        return JsonHelper::decode($content);
    }

    protected function throwEmptyContentExceptionIfEmpty($data)
    {
        if (empty($data)) {
            throw new EmptyContentException();
        }
    }
}