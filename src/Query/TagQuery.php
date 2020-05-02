<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use Jakim\Helper\JsonHelper;
use Jakim\Hydrator\ModelHydrator;
use jakim\ig\Endpoint;
use Jakim\Map\AccountInfo;
use Jakim\Map\EdgeMedia;
use Jakim\Map\ExploreTags;
use Jakim\Model\Account;
use Jakim\Model\MediaCollection;
use Jakim\Model\Tag;

class TagQuery extends Query
{

    /**
     * @param string $tagName
     * @return \Jakim\Model\Tag|\Jakim\Model\ModelInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findOneByName(string $tagName): Tag
    {
        $url = Endpoint::exploreTags($tagName);
        $response = $this->IGClient->get($url);
        $content = $response->getContent();

        $content = JsonHelper::decode($content);
        $hydrator = new ModelHydrator((new ExploreTags())->config());

        return $hydrator->hydrate(new Tag(), $content);
    }

    /**
     * @param string $tagName
     * @return \Jakim\Model\MediaCollection|\Jakim\Model\ModelInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findLatestMedia(string $tagName): MediaCollection
    {
        $url = Endpoint::exploreTags($tagName);

        $response = $this->IGClient->get($url);
        $content = $response->getContent();

        $content = JsonHelper::decode($content);
        $hydrator = new ModelHydrator((new EdgeMedia(EdgeMedia::EXPLORE_TAGS_HASHTAG_MEDIA_ENVELOPE))->config());

        return $hydrator->hydrate(new MediaCollection(), $content);
    }

    /**
     * @param string $tagName
     * @return \Jakim\Model\MediaCollection|\Jakim\Model\ModelInterface
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findTopPosts(string $tagName): MediaCollection
    {
        $url = Endpoint::exploreTags($tagName);

        $response = $this->IGClient->get($url);
        $content = $response->getContent();

        $content = JsonHelper::decode($content);
        $hydrator = new ModelHydrator((new EdgeMedia(EdgeMedia::EXPLORE_TAGS_TOP_POSTS_ENVELOPE))->config());

        return $hydrator->hydrate(new MediaCollection(), $content);
    }
}