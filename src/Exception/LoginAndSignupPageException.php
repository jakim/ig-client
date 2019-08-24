<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 24/08/2019
 */

namespace Jakim\Exception;


use Exception;

class LoginAndSignupPageException extends Exception
{
    protected $message = 'Too many requests.';
}