<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 23.03.2018
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use jakim\ig\Endpoint;
use Jakim\Mapper\MediaDetails;
use Jakim\Model\Post;

class PostQuery extends Query
{
    protected $findOneByShortcode;

    public function __construct($httpClient, MediaDetails $findOneByShortcode)
    {
        parent::__construct($httpClient);
        $this->findOneByShortcode = $findOneByShortcode;
    }

    public function findOneByShortcode(string $shortCode, $relations = false): Post
    {
        $url = Endpoint::mediaDetails($shortCode);

        return $this->createResult($url, $this->findOneByShortcode, $relations);
    }
}