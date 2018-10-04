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
use Jakim\Model\Post;
use Jakim\Model\Tag;

class TagQuery extends Query
{
    protected $exploreTagsMapper;

    public function __construct($httpClient, ExploreTags $exploreTagsMapper = null)
    {
        parent::__construct($httpClient);
        $this->exploreTagsMapper = $exploreTagsMapper ?? new ExploreTags();
    }

    public function findOne(string $tagName): Tag
    {
        $url = Endpoint::exploreTags($tagName);
        $data = $this->fetchContentAsArray($url);

        $this->throwEmptyContentExceptionIfEmpty($data);

        $data = $this->exploreTagsMapper->normalizeData(Tag::class, $data);

        return $this->exploreTagsMapper->populate(Tag::class, $data);
    }

    public function findTopPosts(string $tagName)
    {
        $url = Endpoint::exploreTags($tagName);
        $data = $this->fetchContentAsArray($url);

        $this->throwEmptyContentExceptionIfEmpty($data);

        $items = $this->exploreTagsMapper->normalizeData(Post::class, $data);
        foreach ($items as $item) {
            yield $this->exploreTagsMapper->populate(Post::class, $item);
        }
    }
}