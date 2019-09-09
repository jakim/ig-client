<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 08/09/2019
 */

namespace Jakim\Model;


class MediaCollection
{
    public $count;

    // related
    /**
     * @var \Jakim\Model\PageInfo|null
     */
    public $pageInfo;

    /**
     * @var \Jakim\Model\Post[]|array|null
     */
    public $posts;
}