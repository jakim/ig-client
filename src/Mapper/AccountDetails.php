<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 14.03.2018
 */

namespace Jakim\Mapper;


use Jakim\Base\Mapper;
use Jakim\Model\Account;

/**
 * Account SharedData.
 *
 * @package Jakim\Mapper
 */
class AccountDetails extends Mapper
{
    public function config(): array
    {
        return [
            'class' => Account::class,
            'envelope' => 'entry_data.ProfilePage.0.graphql.user',
            'properties' => [
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
                'isVerified' => 'is_verified',
                'isBusiness' => 'is_business_account',
                'businessCategory' => 'business_category_name',
            ],
        ];
    }
}