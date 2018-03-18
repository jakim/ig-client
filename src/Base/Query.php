<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Base;


abstract class Query
{
    /**
     * Psr7 compatible client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }
}