<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Contract;


interface MapperMultipleInterface
{
    public function populateMultiple(string $class, array $data, int $limit = 10): \Generator;
}