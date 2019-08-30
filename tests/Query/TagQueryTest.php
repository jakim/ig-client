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
use Jakim\Model\Post;
use Jakim\Model\Tag;
use PHPUnit\Framework\TestCase;

class TagQueryTest extends TestCase
{
    protected $tagData;

    /**
     * @var \Jakim\Model\Tag
     */
    protected $tagModel;

    /**
     * @var \Jakim\Model\Post
     */
    protected $firstPostModel;

    /**
     * @var \Jakim\Model\Account
     */
    protected $firstPostAccountModel;

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

        $this->assertInstanceOf(Generator::class, $posts);
        $this->assertContainsOnlyInstancesOf(Post::class, $posts);

        $query = new TagQuery($this->httpClient([$this->tagData]));
        $posts = $query->findTopPosts('aktywnemazury');
        $this->assertEquals($this->firstPostModel, $posts->current());

        $query = new TagQuery($this->httpClient([$this->tagData]));
        $posts = $query->findTopPosts('aktywnemazury', true);
        $this->firstPostModel->account = $this->firstPostAccountModel;
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
        $model->media = 23258;
        $model->topPostsOnly = false;
        $model->likes = 19669;
        $model->minLikes = 518;
        $model->maxLikes = 11051;
        $model->comments = 714;
        $model->minComments = 3;
        $model->maxComments = 204;
        $this->tagModel = $model;

        $model = new Post();
        $model->id = '2098027880902919499';
        $model->caption = '#AktywneMazury
Mazury w obiektywie 📷: @photo_dota
----- Oznaczaj zdjęcia, zostań wyróżniony na największym, mazurskim profilu!
 @aktywnemazury
 @aktywnemazury
---
Tag #AktywneMazury
Follow @aktywnemazury
-----
#mazury
#niebo#chmury#pole#droga#zboże';
        $model->shortcode = 'B0dsT3qFYVL';
        $model->comments = 3;
        $model->takenAt = 1564324434;
        $model->url = 'https://scontent-waw1-1.cdninstagram.com/vp/c134279d2a60a9ce1ab9d4a48d78f2c5/5DCB1E76/t51.2885-15/e35/66292103_431372087452059_3822089688326988513_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com';
        $model->likes = 518;
        $model->isVideo = false;
        $model->videoViews = null;
        $model->typename = 'GraphImage';
        $model->accessibilityCaption = 'Obraz może zawierać: chmura, niebo, trawa, na zewnątrz i przyroda';
        $this->firstPostModel = $model;

        $account = new Account();
        $account->id = '2080433615';
        $this->firstPostAccountModel = $account;
    }
}
