<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 23.03.2018
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use Jakim\Helper\JsonHelper;
use Jakim\Hydrator\ModelHydrator;
use jakim\ig\Endpoint;
use Jakim\Map\MediaDetails;
use Jakim\Model\Post;

class PostQuery extends Query
{
    /**
     * @param string $shortCode
     * @return \Jakim\Model\Post|\Jakim\Model\ModelInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findOneByShortcode(string $shortCode): Post
    {
        $url = Endpoint::mediaDetails($shortCode);

        $response = $this->IGClient->get($url);
        $content = $response->getContent();


        $content = JsonHelper::decode($content);
        $hydrator = new ModelHydrator((new MediaDetails())->config());

        return $hydrator->hydrate(new Post(), $content);
    }
}