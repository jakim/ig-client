<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 20.04.2018
 */

namespace Jakim\Map;


use Jakim\Model\Account;

class AccountInfo implements MapInterface
{
    public function config(): array
    {
        return [
            self::MODEL => Account::class,
            self::ENVELOPE => 'user',
            self::PROPERTIES => [
                'id' => 'pk',
                'profilePicUrl' => 'profile_pic_url',
                'username' => 'username',
            ],
        ];
    }
}