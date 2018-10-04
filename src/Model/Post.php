<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 09.03.2018
 */

namespace Jakim\Model;


class Post
{
    public $id;
    public $shortcode;
    public $url;
    public $isVideo;
    public $videoViews;
    public $videoUrl;
    public $caption;
    public $takenAt;
    public $likes;
    public $comments;
    public $typename; //GraphImage,GraphVideo,GraphSidecar

    // related
    /**
     * @var \Jakim\Model\Account|null
     */
    public $account;

    /**
     * @var \Jakim\Model\Location|null
     */
    public $location;
}