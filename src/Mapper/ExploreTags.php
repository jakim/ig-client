<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Mapper;


use Jakim\Base\Mapper;
use Jakim\Contract\MapperInterface;
use Jakim\Helper\ArrayHelper;
use Jakim\Model\Tag;

class ExploreTags extends Mapper implements MapperInterface
{
    protected function map(): array
    {
        return [
            Tag::class => [
                'envelope' => 'graphql.hashtag',
                'item' => [
                    'name' => 'name',
                    'media' => 'edge_hashtag_to_media.count',
                    'topPostsOnly' => 'is_top_media_only',

                    'likes' => function ($data) use (&$likes) {
                        $likes = ArrayHelper::getColumn(ArrayHelper::getValue($data, 'edge_hashtag_to_top_posts.edges'), 'node.edge_liked_by.count');
                        sort($likes, SORT_ASC);

                        return array_sum($likes);
                    },
                    'minLikes' => function () use (&$likes) {
                        return $likes['0'];
                    },
                    'maxLikes' => function () use (&$likes) {
                        return end($likes);
                    },

                    'comments' => function ($data) use (&$comments) {
                        $comments = ArrayHelper::getColumn(ArrayHelper::getValue($data, 'edge_hashtag_to_top_posts.edges'), 'node.edge_media_to_comment.count');
                        sort($comments, SORT_ASC);

                        return array_sum($comments);
                    },
                    'minComments' => function () use (&$comments) {
                        return $comments['0'];
                    },
                    'maxComments' => function () use (&$comments) {
                        return end($comments);
                    },
                ],
            ],
        ];
    }
}