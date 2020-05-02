<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 09/09/2019
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use Jakim\Helper\JsonHelper;
use Jakim\Hydrator\ModelHydrator;
use jakim\ig\Endpoint;
use Jakim\Map\EdgeMedia;
use Jakim\Model\MediaCollection;

class MediaGraphqlQuery extends Query
{
    protected $map;

    public function __construct($httpClient, EdgeMedia $map)
    {
        parent::__construct($httpClient);
        $this->map = $map;
    }

    public function withMap(EdgeMedia $map)
    {
        $new = clone $this;
        $new->map = $map;

        return $new;
    }

    /**
     * @param string $queryHash
     * @param array $variables
     * @return \Jakim\Model\MediaCollection|\Jakim\Model\ModelInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findMedia(string $queryHash, array $variables): MediaCollection
    {
        $url = Endpoint::createUrl('/graphql/query/', [
            'query_hash' => $queryHash,
            'variables' => $variables,
        ]);

        $response = $this->IGClient->get($url);
        $content = $response->getContent();


        $content = JsonHelper::decode($content);
        $hydrator = new ModelHydrator($this->map->config());

        return $hydrator->hydrate(new MediaCollection(), $content);
    }
}