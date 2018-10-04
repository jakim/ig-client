<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Query;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jakim\Model\Post;
use Jakim\Model\Tag;
use PHPUnit\Framework\TestCase;

class TagQueryTest extends TestCase
{
    protected $tagData;
    protected $tagModel;
    protected $firstPostModel;

    public function testFindOne()
    {
        $query = new TagQuery($this->httpClient([$this->tagData]));
        $tag = $query->findOne('aktywnemazury');
        $this->assertEquals($this->tagModel, $tag);
    }

    public function testFindTopPosts()
    {
        $query = new TagQuery($this->httpClient([$this->tagData]));
        $posts = $query->findTopPosts('aktywnemazury');

        $this->assertInstanceOf(\Generator::class, $posts);
        $this->assertContainsOnlyInstancesOf(Post::class, $posts);

        $query = new TagQuery($this->httpClient([$this->tagData]));
        $posts = $query->findTopPosts('aktywnemazury');
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
        $model->name = 'aktywnemazury';
        $model->media = 16336;
        $model->topPostsOnly = false;
        $model->likes = 1254;
        $model->minLikes = 64;
        $model->maxLikes = 336;
        $model->comments = 44;
        $model->minComments = 0;
        $model->maxComments = 25;
        $this->tagModel = $model;

        $model = new Post();
        $model->id = '1868727993151374809';
        $model->caption = '#fotozakreceni #landscapes #mazury #pieknemazury #mazurskiejeziora #aktywnemazury #magicofpoland #landscape #landscapephotography #landscapeporn #mazurywonderofnature #mazurycudnatury #photography #road #krajobraz #nature #viadernypl #earthescope';
        $model->shortcode = 'BnvDjV1ncXZ';
        $model->comments = 2;
        $model->takenAt = 1536989758;
        $model->url = 'https://scontent-waw1-1.cdninstagram.com/vp/768fce07a62cc3138d397da2085fdfee/5C5C6A31/t51.2885-15/e35/41192720_311098896109012_3099403454891343286_n.jpg';
        $model->likes = 123;
        $model->isVideo = false;
        $model->videoViews = null;
        $model->typename = 'GraphImage';
        $this->firstPostModel = $model;
    }
}
