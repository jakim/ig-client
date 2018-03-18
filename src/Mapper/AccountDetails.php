<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 14.03.2018
 */

namespace Jakim\Mapper;


use Jakim\Base\Mapper;
use Jakim\Contract\MapperInterface;
use Jakim\Model\Account;
use Jakim\Model\Post;

class AccountDetails extends Mapper implements MapperInterface
{
    protected function map(): array
    {
        return [
            Account::class => [
                'envelope' => 'graphql.user',
                'item' => [
                    'biography' => 'biography',
                    'externalUrl' => 'external_url',
                    'followedBy' => 'edge_followed_by.count',
                    'follows' => 'edge_follow.count',
                    'fullName' => 'full_name',
                    'id' => 'id',
                    'isPrivate' => 'is_private',
                    'profilePicUrl' => 'profile_pic_url_hd',
                    'username' => 'username',
                    'media' => 'edge_owner_to_timeline_media.count',
                ],
            ],
            Post::class => [
                'envelope' => 'graphql.user.edge_owner_to_timeline_media.edges',
                'item' => [
                    'id' => 'node.id',
                    'shortcode' => 'node.shortcode',
                    'url' => 'node.display_url',
                    'caption' => 'node.edge_media_to_caption.edges.0.node.text',
                    'likes' => 'node.edge_media_preview_like.count',
                    'comments' => 'node.edge_media_to_comment.count',
                    'takenAt' => 'node.taken_at_timestamp',
                    'isVideo' => 'node.is_video',
                ],
            ],
        ];
    }
}