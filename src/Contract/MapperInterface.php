<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 14.03.2018
 */

namespace Jakim\Contract;


interface MapperInterface
{
    public function populate(string $class, array $data);
}