<?php
/**
 * Created for IG Monitoring.
 * User: jakim <pawel@jakimowski.info>
 * Date: 2018-10-04
 */

namespace Jakim\Exception;


class EmptyContentException extends \Exception
{
    protected $message = 'Content is empty.';
}