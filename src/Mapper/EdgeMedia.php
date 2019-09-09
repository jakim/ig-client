<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 14.03.2018
 */

namespace Jakim\Mapper;


use Jakim\Base\Mapper;
use Jakim\Model\Account;
use Jakim\Model\Location;
use Jakim\Model\MediaCollection;
use Jakim\Model\PageInfo;
use Jakim\Model\Post;

/**
 * Account SharedData.
 *
 * @package Jakim\Mapper
 */
class EdgeMedia extends Mapper
{
    const ACCOUNT_DETAILS_ENVELOPE = 'entry_data.ProfilePage.0.graphql.user.edge_owner_to_timeline_media';
    const EXPLORE_TAGS_HASHTAG_MEDIA_ENVELOPE = 'graphql.hashtag.edge_hashtag_to_media';
    const EXPLORE_TAGS_TOP_POSTS_ENVELOPE = 'graphql.hashtag.edge_hashtag_to_top_posts';
    const GRAPHQL_HASHTAG_MEDIA_ENVELOPE = 'data.hashtag.edge_hashtag_to_media';
    const GRAPHQL_ACCOUNT_MEDIA_ENVELOPE = 'data.user.edge_owner_to_timeline_media';

    protected $envelope = self::ACCOUNT_DETAILS_ENVELOPE;

    public function __construct(string $envelope = self::ACCOUNT_DETAILS_ENVELOPE)
    {
        $this->setEnvelope($envelope);
    }

    public function setEnvelope($key)
    {
        $this->envelope = $key;
    }

    public function config(): array
    {
        return [
            'class' => MediaCollection::class,
            'envelope' => $this->envelope,
            'properties' => [
                'count' => 'count',
            ],
            'relations' => [
                'posts' => [
                    'multiple' => true,
                    'class' => Post::class,
                    'envelope' => 'edges',
                    'properties' => [
                        'id' => 'node.id',
                        'shortcode' => 'node.shortcode',
                        'url' => 'node.display_url',
                        'caption' => 'node.edge_media_to_caption.edges.0.node.text',
                        'likes' => 'node.edge_media_preview_like.count',
                        'comments' => 'node.edge_media_to_comment.count',
                        'takenAt' => 'node.taken_at_timestamp',
                        'isVideo' => 'node.is_video',
                        'videoViews' => 'node.video_view_count',
                        'videoUrl' => 'node.video_url',
                        'typename' => 'node.__typename',
                        'accessibilityCaption' => 'node.accessibility_caption',
                    ],
                    'relations' => [
                        'account' => [
                            'class' => Account::class,
                            'envelope' => 'node.owner',
                            'properties' => [
                                'id' => 'id',
                                'username' => 'username',
                            ],
                        ],
                        'location' => [
                            'class' => Location::class,
                            'envelope' => 'node.location',
                            'properties' => [
                                'id' => 'id',
                                'hasPublicPage' => 'has_public_page',
                                'name' => 'name',
                                'slug' => 'slug',
                            ],
                        ],
                    ],
                ],
                'pageInfo' => [
                    'class' => PageInfo::class,
                    'envelope' => 'page_info',
                    'properties' => [
                        'hasNextPage' => 'has_next_page',
                        'endCursor' => 'end_cursor',
                    ],
                ],
            ],
        ];
    }
}