<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use jakim\ig\Endpoint;
use Jakim\Mapper\ExploreTags;
use Jakim\Mapper\TagGraphql;
use Jakim\Model\Post;
use Jakim\Model\Tag;

class TagQuery extends Query
{
    protected $exploreTagsMapper;
    protected $tagGraphqlMapper;

    public function __construct($httpClient, ExploreTags $exploreTagsMapper = null)
    {
        parent::__construct($httpClient);
        $this->exploreTagsMapper = $exploreTagsMapper ?? new ExploreTags();
        $this->tagGraphqlMapper = $tagGraphqlMapper ?? new TagGraphql();
    }

    public function findOne(string $tagName): Tag
    {
        $data = $this->fetchData($tagName);
        $data = $this->exploreTagsMapper->normalizeData(Tag::class, $data);

        return $this->exploreTagsMapper->populate(Tag::class, $data);
    }

    public function findTopPosts(string $tagName, bool $relations = false)
    {
        $this->exploreTagsMapper->postsEnvelope = ExploreTags::TOP_POSTS_ENVELOPE;

        $data = $this->fetchData($tagName);
        $items = $this->exploreTagsMapper->normalizeData(Post::class, $data);
        foreach ($items as $item) {
            yield $this->exploreTagsMapper->populate(Post::class, $item, $relations);
        }
    }

    public function findMedia(string $tagName, bool $relations = false)
    {
        $this->exploreTagsMapper->postsEnvelope = ExploreTags::MEDIA_ENVELOPE;

        $data = $this->fetchData($tagName);
        $items = $this->exploreTagsMapper->normalizeData(Post::class, $data);
        foreach ($items as $item) {
            yield $this->exploreTagsMapper->populate(Post::class, $item, $relations);
        }
    }

    /**
     * @param string $tagName
     * @return array|null
     * @throws \Jakim\Exception\EmptyContentException
     */
    private function fetchData(string $tagName)
    {
        $url = Endpoint::exploreTags($tagName);
        $data = $this->fetchContentAsArray($url);

        $this->throwEmptyContentExceptionIfEmpty($data);

        return $data;
    }
}