<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 07/09/2019
 */

namespace Jakim\Model;


class PageInfo implements ModelInterface
{
    public $hasNextPage = false;
    public $endCursor = null;
}