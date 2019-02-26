<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 2019-02-26
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use jakim\ig\Endpoint;
use Jakim\Mapper\ExploreLocations;
use Jakim\Model\Location;
use Jakim\Model\Post;
use Jakim\Model\Tag;

class LocationQuery extends Query
{
    protected $exploreLocationsMapper;

    public function __construct($httpClient, ExploreLocations $exploreLocationsMapper = null)
    {
        parent::__construct($httpClient);
        $this->exploreLocationsMapper = $exploreLocationsMapper ?? new ExploreLocations();
    }

    public function findOne(string $id): Location
    {
        $url = Endpoint::exploreLocations($id);
        $data = $this->fetchContentAsArray($url);

        $this->throwEmptyContentExceptionIfEmpty($data);

        $data = $this->exploreLocationsMapper->normalizeData(Location::class, $data);

        return $this->exploreLocationsMapper->populate(Location::class, $data);
    }

    public function findTopPosts(string $id)
    {
        $url = Endpoint::exploreLocations($id);
        $data = $this->fetchContentAsArray($url);

        $this->throwEmptyContentExceptionIfEmpty($data);

        $items = $this->exploreLocationsMapper->normalizeData(Post::class, $data);
        foreach ($items as $item) {
            yield $this->exploreLocationsMapper->populate(Post::class, $item);
        }
    }
}