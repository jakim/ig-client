<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 2019-05-21
 */

namespace Jakim\Exception;


use Exception;

class RestrictedProfileException extends Exception
{
    protected $message = 'Restricted profile.';
}