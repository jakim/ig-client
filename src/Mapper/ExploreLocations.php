<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Mapper;


use Jakim\Helper\ArrayHelper;
use Jakim\Model\Account;
use Jakim\Model\Location;
use Jakim\Model\Post;
use Jakim\Model\Tag;

class ExploreLocations extends AccountDetails
{
    protected function map(): array
    {
        $class = Post::class;
        $postMap = ArrayHelper::getValue(parent::map(), "$class.item");

        return [
            Location::class => [
                'envelope' => 'graphql.location',
                'item' => [
                    'id' => 'id',
                    'name' => 'name',
                    'slug' => 'slug',
                    'hasPublicPage' => 'has_public_page',
                    'lat' => 'lat',
                    'lng' => 'lng',
                    'media' => 'edge_location_to_media.count',
                ],
            ],
            Post::class => [
                'envelope' => 'graphql.location.edge_location_to_top_posts.edges',
                'item' => $postMap,
                'relations' => [
                    'account' => Account::class,
                ],
            ],
            Account::class => [
                'envelope' => 'node.owner',
                'item' => [
                    'id' => 'id'
                ],
            ]
        ];
    }
}