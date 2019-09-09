<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 09.03.2018
 */

namespace Jakim\Model;

class Account
{
    public $id;
    public $username;
    public $profilePicUrl;
    public $fullName;
    public $biography;
    public $externalUrl;
    public $followedBy;
    public $follows;
    public $media;
    public $isPrivate;
    public $isVerified;
    public $isBusiness;
    public $businessCategory;
}