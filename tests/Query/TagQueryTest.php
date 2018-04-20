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

    public function testFindOne()
    {
        $query = new TagQuery($this->httpClient([$this->tagData]));
        $tag = $query->findOne('cojarobie');
        $this->assertEquals($this->tagModel, $tag);
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
    }
}
