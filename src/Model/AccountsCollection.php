<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 24/12/2019
 */

namespace Jakim\Model;


class AccountsCollection
{
    public $count;

    // related
    /**
     * @var \Jakim\Model\PageInfo|null
     */
    public $pageInfo;

    /**
     * @var \Jakim\Model\Account[]|array|null
     */
    public $accounts;
}