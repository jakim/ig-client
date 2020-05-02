<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 01/05/2020
 */

namespace Jakim\Map;


interface MapInterface
{
    const MODEL = 'model';
    const ENVELOPE = 'envelope';
    const PROPERTIES = 'properties';
    const MULTIPLE = 'multiple';

    public function config(): array;
}