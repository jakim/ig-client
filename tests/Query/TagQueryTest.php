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
use Jakim\Mapper\EdgeMedia;
use Jakim\Mapper\ExploreTags;
use Jakim\Model\Account;
use Jakim\Model\MediaCollection;
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
    protected $firstMediaModel;

    /**
     * @var \Jakim\Model\Account
     */
    protected $firstMediaAccountModel;

    /**
     * @var \Jakim\Model\Post
     */
    protected $firstTopPostsModel;

    /**
     * @var \Jakim\Model\Account
     */
    protected $firstTopPostAccountModel;

    public function testFindOne()
    {
        $query = new TagQuery($this->httpClient([$this->tagData]), new ExploreTags());
        $tag = $query->findOneByName('aktywnemazury');

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals($this->tagModel, $tag);
    }

    public function testFindLastMedia()
    {
        $edgeMedia = new EdgeMedia(EdgeMedia::EXPLORE_TAGS_HASHTAG_MEDIA_ENVELOPE);

        $query = new TagQuery($this->httpClient([$this->tagData]), null, $edgeMedia);
        $mediaCollection = $query->findLatestMedia('aktywnemazury', false);

        $this->assertInstanceOf(MediaCollection::class, $mediaCollection);
        $this->assertEquals(23258, $mediaCollection->count);
        $this->assertNull($mediaCollection->pageInfo);
        $this->assertNull($mediaCollection->posts);

        $query = new TagQuery($this->httpClient([$this->tagData]), null, $edgeMedia);
        $mediaCollection = $query->findLatestMedia('aktywnemazury', true);
        $this->assertNotNull($mediaCollection->posts);

        $this->firstMediaModel->account = $this->firstMediaAccountModel;
        $this->assertEquals($this->firstMediaModel, $mediaCollection->posts['0']);
    }

    public function testFindTopPosts()
    {
        $edgeMedia = new EdgeMedia(EdgeMedia::EXPLORE_TAGS_TOP_POSTS_ENVELOPE);

        $query = new TagQuery($this->httpClient([$this->tagData]), null, null, $edgeMedia);
        $mediaCollection = $query->findTopPosts('aktywnemazury', false);

        $this->assertInstanceOf(MediaCollection::class, $mediaCollection);
        $this->assertNull($mediaCollection->count);
        $this->assertNull($mediaCollection->pageInfo);
        $this->assertNull($mediaCollection->posts);

        $query = new TagQuery($this->httpClient([$this->tagData]), null, null, $edgeMedia);
        $mediaCollection = $query->findTopPosts('aktywnemazury', true);
        $this->assertNull($mediaCollection->count);
        $this->assertNull($mediaCollection->pageInfo);
        $this->assertNotNull($mediaCollection->posts);

        $this->firstTopPostsModel->account = $this->firstTopPostAccountModel;
        $this->assertEquals($this->firstTopPostsModel, $mediaCollection->posts['0']);
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
        $model->id = '17843762590043641';
        $model->name = 'aktywnemazury';
        $model->media = 23258;
        $model->topPostsOnly = false;
        $this->tagModel = $model;

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
        $this->firstTopPostAccountModel = $account;
    }
}
