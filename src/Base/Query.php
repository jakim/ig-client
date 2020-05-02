<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Base;


use Jakim\IGClient;

abstract class Query
{
    protected IGClient $IGClient;

    /**
     * Query constructor.
     *
     * @param \Jakim\IGClient $IGClient
     */
    public function __construct(IGClient $IGClient)
    {
        $this->IGClient = $IGClient;
    }

}