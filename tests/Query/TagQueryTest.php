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
    protected $firstTopPostsModel;

    /**
     * @var \Jakim\Model\Account
     */
    protected $firstPostAccountModel;

    /**
     * @var \Jakim\Model\Post
     */
    protected $firstMediaModel;

    /**
     * @var \Jakim\Model\Account
     */
    protected $firstMediaAccountModel;

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
        $this->assertEquals($this->firstTopPostsModel, $posts->current());

        $query = new TagQuery($this->httpClient([$this->tagData]));
        $posts = $query->findTopPosts('aktywnemazury', true);
        $this->firstTopPostsModel->account = $this->firstPostAccountModel;
        $this->assertEquals($this->firstTopPostsModel, $posts->current());
    }

    public function testFindMedia()
    {
        $query = new TagQuery($this->httpClient([$this->tagData]));
        $posts = $query->findMedia('aktywnemazury');

        $this->assertInstanceOf(Generator::class, $posts);
        $this->assertContainsOnlyInstancesOf(Post::class, $posts);

        $query = new TagQuery($this->httpClient([$this->tagData]));
        $posts = $query->findMedia('aktywnemazury');
        $this->assertEquals($this->firstMediaModel, $posts->current());

        $query = new TagQuery($this->httpClient([$this->tagData]));
        $posts = $query->findMedia('aktywnemazury', true);
        $this->firstMediaModel->account = $this->firstMediaAccountModel;
        $this->assertEquals($this->firstMediaModel, $posts->current());
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
        $this->firstTopPostsModel = $model;

        $account = new Account();
        $account->id = '2080433615';
        $this->firstPostAccountModel = $account;

        $model2 = new Post();
        $model2->id = '2098720829868392659';
        $model2->caption = '#giycko #port #mazury #odpoczynek';
        $model2->shortcode = 'B0gJ3mrBMDT';
        $model2->comments = 0;
        $model2->takenAt = 1564407040;
        $model2->url = 'https://scontent-waw1-1.cdninstagram.com/vp/4d9e5bbe8efab26f69b0da6ed618d8bf/5DE6DD88/t51.2885-15/fr/e15/s1080x1080/67442218_1360998334058330_2606638084047220141_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com';
        $model2->likes = 1;
        $model2->isVideo = false;
        $model2->videoViews = null;
        $model2->typename = 'GraphImage';
        $model2->accessibilityCaption = 'Obraz mo';
        $this->firstMediaModel = $model2;

        $account2 = new Account();
        $account2->id = '2876261960';
        $this->firstMediaAccountModel = $account2;
    }
}
