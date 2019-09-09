<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 23.03.2018
 */

namespace Jakim\Mapper;


use Jakim\Base\Mapper;
use Jakim\Model\Account;
use Jakim\Model\Location;
use Jakim\Model\Post;

/**
 * Specific media mapper.
 *
 * @package Jakim\Mapper
 */
class MediaDetails extends Mapper
{
    public function config(): array
    {
        return [
            'class' => Post::class,
            'envelope' => 'graphql.shortcode_media',
            'properties' => [
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
                'account' => [
                    'class' => Account::class,
                    'envelope' => 'owner', //related to parent map
                    'properties' => [
                        'id' => 'id',
                        'username' => 'username',
                        'profilePicUrl' => 'profile_pic_url',
                        'fullName' => 'full_name',
                        'isPrivate' => 'is_private',
                    ],
                ],
                'location' => [
                    'class' => Location::class,
                    'envelope' => 'location',
                    'properties' => [
                        'id' => 'id',
                        'hasPublicPage' => 'has_public_page',
                        'name' => 'name',
                        'slug' => 'slug',
                        'addressJson' => 'address_json',
                    ],
                ],
                'sponsor' => [
                    'class' => Account::class,
                    'envelope' => 'edge_media_to_sponsor_user.edges.0.node.sponsor', //related to parent map
                    'properties' => [
                        'id' => 'id',
                        'username' => 'username',
                    ],
                ],
                'tagged' => [
                    'multiple' => true,
                    'class' => Account::class,
                    'envelope' => 'edge_media_to_tagged_user.edges',
                    'properties' => [
                        'id' => 'node.user.id',
                        'username' => 'node.user.username',
                        'profilePicUrl' => 'node.user.profile_pic_url',
                        'fullName' => 'node.user.full_name',
                        'isVerified' => 'node.user.is_verified',
                    ],
                ],
            ],
        ];
    }
}