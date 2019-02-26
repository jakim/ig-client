<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Query;

use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jakim\Model\Location;
use Jakim\Model\Post;
use PHPUnit\Framework\TestCase;

class LocationQueryTest extends TestCase
{
    protected $locationData;
    protected $locationModel;
    protected $firstPostModel;

    public function testFindOne()
    {
        $query = new LocationQuery($this->httpClient([$this->locationData]));
        $location = $query->findOne('567581085');
        $this->assertEquals($this->locationModel, $location);
    }

    public function testFindTopPosts()
    {
        $query = new LocationQuery($this->httpClient([$this->locationData]));
        $posts = $query->findTopPosts('567581085');

        $this->assertInstanceOf(Generator::class, $posts);
        $this->assertContainsOnlyInstancesOf(Post::class, $posts);

        $query = new LocationQuery($this->httpClient([$this->locationData]));
        $posts = $query->findTopPosts('567581085');
        $this->assertEquals($this->firstPostModel, $posts->current());
    }

    protected function httpClient(array $responses = ['{}'])
    {
        $mock = new MockHandler(array_map(function ($response) {
            return new Response(200, ['Content-Type' => 'application/json'], $response);
        }, $responses));
        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }

    protected function setUp()
    {
        $this->locationData = file_get_contents(__DIR__ . '/../_data/explore_locations.json');

        $model = new Location();
        $model->id = '567581085';
        $model->name = 'South Georgia and the South Sandwich Islands';
        $model->slug = 'south-georgia-and-the-south-sandwich-islands';
        $model->media = 11420;
        $model->hasPublicPage = true;
        $model->lat = -54.25;
        $model->lng = -36.75;
        $this->locationModel = $model;

        $model = new Post();
        $model->id = '1985520293813609175';
        $model->caption = 'Decay and death.';
        $model->shortcode = 'BuN_C__FuLX';
        $model->comments = 25;
        $model->takenAt = 1550912485;
        $model->url = 'https://scontent-waw1-1.cdninstagram.com/vp/79863963d1523c9b68d905737b5c31c5/5D262ACE/t51.2885-15/e35/51809916_263635714551718_1096628458811833908_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com';
        $model->likes = 14707;
        $model->isVideo = false;
        $model->videoViews = null;
        $model->typename = null;
        $this->firstPostModel = $model;
    }
}
