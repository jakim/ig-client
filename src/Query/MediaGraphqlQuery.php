<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 09/09/2019
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use jakim\ig\Endpoint;
use Jakim\Mapper\EdgeMedia;
use Jakim\Model\MediaCollection;

class MediaGraphqlQuery extends Query
{
    protected $findMedia;

    public function __construct($httpClient, EdgeMedia $findMedia)
    {
        parent::__construct($httpClient);
        $this->findMedia = $findMedia;
    }

    public function findMedia(string $queryHash, array $variables): MediaCollection
    {
        $url = Endpoint::createUrl('/graphql/query/', [
            'query_hash' => $queryHash,
            'variables' => $variables,
        ]);

        return $this->createResult($url, $this->findMedia, true);
    }
}