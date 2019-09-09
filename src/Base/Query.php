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

    /**
     * @param string $url
     * @param \Jakim\Base\Mapper $mapper
     * @param bool $relations
     * @return mixed
     * @throws \Jakim\Exception\EmptyContentException
     * @throws \Jakim\Exception\LoginAndSignupPageException
     * @throws \Jakim\Exception\RestrictedProfileException
     */
    protected function createResult(string $url, Mapper $mapper, bool $relations)
    {
        $content = $this->fetchContentAsArray($url);

        $this->throwEmptyContentExceptionIfEmpty($content);

        $config = $mapper->config();
        $data = $mapper->getData($content, $config);

        return $mapper->createModel($data, $config, $relations);
    }

    protected function throwEmptyContentExceptionIfEmpty($content)
    {
        if (empty($content)) {
            throw new EmptyContentException();
        }
    }
}