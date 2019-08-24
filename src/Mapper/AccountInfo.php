<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 20.04.2018
 */

namespace Jakim\Mapper;


use Jakim\Base\Mapper;
use Jakim\Contract\MapperInterface;
use Jakim\Model\Account;

class AccountInfo extends Mapper implements MapperInterface
{

    /**
     * Attributes map.
     *
     * @return array
     */
    protected function map(): array
    {
        return [
            Account::class => [
                'envelope' => 'user',
                'item' => [
                    'profilePicUrl' => 'profile_pic_url',
                    'username' => 'username',
                ],
            ],
        ];
    }
}