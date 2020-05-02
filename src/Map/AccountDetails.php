<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 14.03.2018
 */

namespace Jakim\Map;


use Jakim\Base\Mapper;
use Jakim\Model\Account;

/**
 * Account SharedData.
 *
 * @package Jakim\Map
 */
class AccountDetails implements MapInterface
{
    public function config(): array
    {
        return [
            self::MODEL => Account::class,
            self::ENVELOPE => 'entry_data.ProfilePage.0.graphql.user',
            self::PROPERTIES => [
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