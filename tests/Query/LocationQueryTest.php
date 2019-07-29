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
use Jakim\Model\Account;
use Jakim\Model\Location;
use Jakim\Model\Post;
use PHPUnit\Framework\TestCase;

class LocationQueryTest extends TestCase
{
    protected $locationData;
    protected $locationModel;
    protected $firstPostModel;
    protected $firstPostAccountModel;

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

        $query = new LocationQuery($this->httpClient([$this->locationData]));
        $this->firstPostModel->account = $this->firstPostAccountModel;
        $posts = $query->findTopPosts('567581085', true);

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
        $model->id = '896111418';
        $model->name = 'Olecko gmina';
        $model->slug = 'olecko-gmina';
        $model->media = 1044;
        $model->hasPublicPage = true;
        $model->lat = 54.0368596215;
        $model->lng = 22.4892421686;
        $this->locationModel = $model;

        $model = new Post();
        $model->id = '1949342796813128970';
        $model->caption = '- Смогла ли я поселиться в твоём сердце? - Да ,ты вломилась в него ,не снимая обуви.. Аракава Наоси "Твоя апрельская ложь"';
        $model->shortcode = 'BsNdPIVg1UK';
        $model->comments = 11;
        $model->takenAt = 1546599791;
        $model->url = 'https://scontent-waw1-1.cdninstagram.com/vp/e635d00c1cb154d56fbe7889cec6a63e/5DE97F46/t51.2885-15/e35/s1080x1080/46652635_388337235043658_8859129959233172182_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com';
        $model->likes = 602;
        $model->isVideo = false;
        $model->videoViews = null;
        $model->typename = null;
        $this->firstPostModel = $model;

        $model = new Account();
        $model->id = '1074188139';
        $this->firstPostAccountModel = $model;
    }
}
