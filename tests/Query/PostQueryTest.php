<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 23.03.2018
 */

namespace Jakim\Query;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jakim\Model\Account;
use Jakim\Model\Location;
use Jakim\Model\Post;
use PHPUnit\Framework\TestCase;

class PostQueryTest extends TestCase
{
    protected $postData;
    protected $postModel;
    protected $postWithLocationData;

    public function testFindOne()
    {
        $query = new PostQuery($this->httpClient([$this->postData]));
        $post = $query->findOne('instagram', true);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($this->postModel, $post);
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
        $this->postData = file_get_contents(__DIR__ . '/../_data/post_details.json');

        $post = new Post();
        $post->id = '1514638571092100627';
        $post->shortcode = 'BUFE8FpDMYT';
        $post->url = 'https://scontent-waw1-1.cdninstagram.com/vp/a8c5fcdcd7308f7c9866043ea7f0d78f/5B34A7D6/t51.2885-15/e35/18512356_1849541752036804_6996195506900697088_n.jpg';
        $post->isVideo = false;
        $post->caption = '#Caferiler : Sonsuz Matem Serisinden - Caferiler';
        $post->comments = 8;
        $post->takenAt = 1494779009;
        $post->likes = 102;
        $post->typename = 'GraphImage';

        $account = new Account();
        $account->id = '3666715406';
        $account->profilePicUrl = 'https://scontent-waw1-1.cdninstagram.com/vp/db1e740d681f3caf34c77802913993ec/5B746D80/t51.2885-19/s150x150/27881193_152057518820705_1143441489582358528_n.jpg';
        $account->username = 'cenkmiratpekcanatti';
        $account->fullName = 'Cenk \'Mirat\' PEKCANATTI';
        $account->isPrivate = false;
        $post->account = $account;

        $location = new Location();
        $location->id = '214250664';
        $location->hasPublicPage = true;
        $location->name = 'Istanbul, Turkey';
        $location->slug = 'istanbul-turkey';
        $post->location = $location;

        $this->postModel = $post;
    }
}
