<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 23.03.2018
 */

namespace Jakim\Mapper;


use Jakim\Base\Mapper;
use Jakim\Contract\MapperInterface;
use Jakim\Model\Post;

class MediaDetails extends Mapper implements MapperInterface
{
    protected function map(): array
    {
        return [
            Post::class => [
                'envelope' => 'graphql.shortcode_media',
                'item' => [
                    'id' => 'id',
                    'shortcode' => 'shortcode',
                    'url' => 'display_url',
                    'caption' => 'edge_media_to_caption.edges.0.node.text',
                    'likes' => 'edge_media_preview_like.count',
                    'comments' => 'edge_media_to_comment.count',
                    'takenAt' => 'taken_at_timestamp',
                    'isVideo' => 'is_video',
                ],
            ],
        ];
    }
}