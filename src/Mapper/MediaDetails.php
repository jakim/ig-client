<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 23.03.2018
 */

namespace Jakim\Mapper;


use Jakim\Base\Mapper;
use Jakim\Contract\MapperInterface;
use Jakim\Model\Account;
use Jakim\Model\Location;
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
                    'comments' => 'edge_media_preview_comment.count',
                    'takenAt' => 'taken_at_timestamp',
                    'isVideo' => 'is_video',
                    'videoViews' => 'video_view_count',
                    'videoUrl' => 'video_url',
                    'typename' => '__typename',
                    'accessibilityCaption' => 'accessibility_caption',
                ],
                'relations' => [
                    'account' => Account::class,
                    'location' => Location::class,
                ],
            ],
            Account::class => [
                'envelope' => 'owner', //related to parent map
                'item' => [
                    'id' => 'id',
                    'username' => 'username',
                    'profilePicUrl' => 'profile_pic_url',
                    'fullName' => 'full_name',
                    'isPrivate' => 'is_private',
                ],
            ],
            Location::class => [
                'envelope' => 'location',
                'item' => [
                    'id' => 'id',
                    'hasPublicPage' => 'has_public_page',
                    'name' => 'name',
                    'slug' => 'slug',
                    'addressJson' => 'address_json',
                ],
            ],
        ];
    }
}