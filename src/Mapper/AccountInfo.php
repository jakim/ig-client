<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 20.04.2018
 */

namespace Jakim\Mapper;


use Jakim\Base\Mapper;
use Jakim\Model\Account;

class AccountInfo extends Mapper
{
    public function config(): array
    {
        return [
            'class' => Account::class,
            'envelope' => 'user',
            'properties' => [
                'profilePicUrl' => 'profile_pic_url',
                'username' => 'username',
            ],
        ];
    }
}