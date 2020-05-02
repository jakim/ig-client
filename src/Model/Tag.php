<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 14.03.2018
 */

namespace Jakim\Model;


class Tag implements ModelInterface
{
    public $id;
    public $name;
    public $media;
    public $topPostsOnly;
}