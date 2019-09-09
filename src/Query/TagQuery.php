<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use jakim\ig\Endpoint;
use Jakim\Mapper\EdgeMedia;
use Jakim\Mapper\ExploreTags;
use Jakim\Model\MediaCollection;
use Jakim\Model\Tag;

class TagQuery extends Query
{
    protected $findOneByName;
    protected $findLatestMedia;
    protected $findTopPosts;

    public function __construct(
        $httpClient,
        ExploreTags $findOneByName = null,
        EdgeMedia $findLatestMedia = null,
        EdgeMedia $findTopPosts = null
    )
    {
        parent::__construct($httpClient);
        $this->findOneByName = $findOneByName;
        $this->findLatestMedia = $findLatestMedia;
        $this->findTopPosts = $findTopPosts;
    }

    public function findOneByName(string $tagName): Tag
    {
        $url = Endpoint::exploreTags($tagName);

        return $this->createResult($url, $this->findOneByName, false);
    }

    public function findLatestMedia(string $tagName, bool $relations = false): MediaCollection
    {
        $url = Endpoint::exploreTags($tagName);

        return $this->createResult($url, $this->findLatestMedia, $relations);
    }

    public function findTopPosts(string $tagName, bool $relations = false): MediaCollection
    {
        $url = Endpoint::exploreTags($tagName);

        return $this->createResult($url, $this->findTopPosts, $relations);
    }
}