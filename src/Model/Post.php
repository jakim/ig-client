<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 09.03.2018
 */

namespace Jakim\Model;


class Post implements ModelInterface
{
    public $id;
    public $shortcode;
    public $url;
    public $likes;
    public $comments;
    public $typename; //GraphImage,GraphVideo,GraphSidecar
    public $isVideo;
    public $videoViews;
    public $videoUrl;
    public $caption;
    public $accessibilityCaption;
    public $takenAt;

    // related
    /**
     * @var \Jakim\Model\Account|null
     */
    public $account;

    /**
     * @var \Jakim\Model\Location|null
     */
    public $location;

    /**
     * @var \Jakim\Model\Account[]|array|null
     */
    public $tagged;

    /**
     * @var \Jakim\Model\Account|null
     */
    public $sponsor;
}