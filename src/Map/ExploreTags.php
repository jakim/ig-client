<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Map;


use Jakim\Base\Mapper;
use Jakim\Model\Tag;

class ExploreTags extends Mapper
{
    public function config(): array
    {
        return [
            MapInterface::MODEL => Tag::class,
            MapInterface::ENVELOPE => 'graphql.hashtag',
            MapInterface::PROPERTIES => [
                'id' => 'id',
                'name' => 'name',
                'media' => 'edge_hashtag_to_media.count',
                'topPostsOnly' => 'is_top_media_only',
            ],
        ];
    }
}