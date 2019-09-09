<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Mapper;


use Jakim\Base\Mapper;
use Jakim\Model\Tag;

class ExploreTags extends Mapper
{
    public function config(): array
    {
        return [
            'class' => Tag::class,
            'envelope' => 'graphql.hashtag',
            'properties' => [
                'id' => 'id',
                'name' => 'name',
                'media' => 'edge_hashtag_to_media.count',
                'topPostsOnly' => 'is_top_media_only',
            ],
        ];
    }
}