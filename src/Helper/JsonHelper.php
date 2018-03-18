<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 14.03.2018
 */

namespace Jakim\Helper;


class JsonHelper
{

    public static function decode(string $json)
    {
        return json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
    }

    public static function encode($value, $options = 320)
    {
        return json_encode($value, $options);
    }
}