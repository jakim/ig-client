<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Query;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jakim\Model\Post;
use Jakim\Model\Tag;
use Jakim\Query\TagQuery;
use PHPUnit\Framework\TestCase;

class TagQueryTest extends TestCase
{
    protected $tagData;
    protected $tagModel;
    protected $firstPostModel;

    public function testFindOne()
    {
        $query = new TagQuery($this->httpClient([$this->tagData]));
        $tag = $query->findOne('cojarobie');
        $this->assertEquals($this->tagModel, $tag);
    }

    public function testFindTopPosts()
    {
        $query = new TagQuery($this->httpClient([$this->tagData]));
        $posts = $query->findTopPosts('cojarobie');

        $this->assertInstanceOf(\Generator::class, $posts);
        $this->assertContainsOnlyInstancesOf(Post::class, $posts);

        $query = new TagQuery($this->httpClient([$this->tagData]));
        $posts = $query->findTopPosts('cojarobie');
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
        $this->tagData = file_get_contents(__DIR__ . '/../_data/explore_tags.json');

        $model = new Tag();
        $model->name = 'cojarobie';
        $model->media = 4202;
        $model->topPostsOnly = false;
        $model->likes = 632;
        $model->minLikes = 9;
        $model->maxLikes = 424;
        $model->comments = 16;
        $model->minComments = 0;
        $model->maxComments = 8;
        $this->tagModel = $model;

        $model = new Post();
        $model->id = '1700840133324304245';
        $model->caption = 'Wtf #cojarobie #niepytaj #czemu #niema #mema';
        $model->shortcode = 'BeamRb-he91';
        $model->comments = 3;
        $model->takenAt = 1516976081;
        $model->url = 'https://instagram.fhen1-1.fna.fbcdn.net/vp/10bbc2e9b8b9e3f9acdc3af1dc708001/5AAEDBEA/t51.2885-15/e35/26869160_1722475194439887_5234250414269923328_n.jpg';
        $model->likes = 20;
        $model->isVideo = true;
        $this->firstPostModel = $model;
    }
}
