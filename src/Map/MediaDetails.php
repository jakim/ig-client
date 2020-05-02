<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 23.03.2018
 */

namespace Jakim\Map;


use Jakim\Base\Mapper;
use Jakim\Model\Account;
use Jakim\Model\Location;
use Jakim\Model\Post;

/**
 * Specific media mapper.
 *
 * @package Jakim\Map
 */
class MediaDetails extends Mapper
{
    public function config(): array
    {
        return [
            MapInterface::MODEL => Post::class,
            MapInterface::ENVELOPE => 'graphql.shortcode_media',
            MapInterface::PROPERTIES => [
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
                'account' => [
                    MapInterface::MODEL => Account::class,
                    MapInterface::ENVELOPE => 'owner', //related to parent map
                    MapInterface::PROPERTIES => [
                        'id' => 'id',
                        'username' => 'username',
                        'profilePicUrl' => 'profile_pic_url',
                        'fullName' => 'full_name',
                        'isPrivate' => 'is_private',
                    ],
                ],
                'location' => [
                    MapInterface::MODEL => Location::class,
                    MapInterface::ENVELOPE => 'location',
                    MapInterface::PROPERTIES => [
                        'id' => 'id',
                        'hasPublicPage' => 'has_public_page',
                        'name' => 'name',
                        'slug' => 'slug',
                        'addressJson' => 'address_json',
                    ],
                ],
                'sponsor' => [
                    MapInterface::MODEL => Account::class,
                    MapInterface::ENVELOPE => 'edge_media_to_sponsor_user.edges.0.node.sponsor', //related to parent map
                    MapInterface::PROPERTIES => [
                        'id' => 'id',
                        'username' => 'username',
                    ],
                ],
                'tagged' => [
                    MapInterface::MULTIPLE => true,
                    MapInterface::MODEL => Account::class,
                    MapInterface::ENVELOPE => 'edge_media_to_tagged_user.edges',
                    MapInterface::PROPERTIES => [
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