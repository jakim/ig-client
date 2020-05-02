<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Query;

use Jakim\IGClient;
use Jakim\Map\EdgeMedia;
use Jakim\Model\Account;
use Jakim\Model\Location;
use Jakim\Model\MediaCollection;
use Jakim\Model\Post;
use Jakim\Model\Tag;
use Jakim\Query\TagQuery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

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
        $query = new TagQuery($this->IGClient([$this->tagData]));
        $tag = $query->findOneByName('aktywnemazury');

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals($this->tagModel, $tag);
    }

    public function testFindLastMedia()
    {
        $query = new TagQuery($this->IGClient([$this->tagData]));
        $mediaCollection = $query->findLatestMedia('aktywnemazury');
        $this->assertNotNull($mediaCollection->posts);

        $this->firstMediaModel->account = $this->firstMediaAccountModel;
        $this->assertEquals($this->firstMediaModel, $mediaCollection->posts['0']);
    }

    public function testFindTopPosts()
    {
        $query = new TagQuery($this->IGClient([$this->tagData]));
        $mediaCollection = $query->findTopPosts('aktywnemazury');
        $this->assertNull($mediaCollection->count);
        $this->assertFalse($mediaCollection->pageInfo->hasNextPage);
        $this->assertNull($mediaCollection->pageInfo->endCursor);
        $this->assertNotNull($mediaCollection->posts);
        $this->assertEquals($this->firstTopPostsModel, $mediaCollection->posts['0']);
    }

    protected function IGClient(array $responses = ['{}'])
    {
        $client = new MockHttpClient(array_map(function ($response) {
            return new MockResponse($response, [
                'http_code' => 200,
                'response_headers' => ['Content-Type' => 'application/json'],
            ]);
        }, $responses));

        return new IGClient($client);
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
        $model2->location = new Location();
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
        $model->account = new Account();
        $model->account->id = '2080433615';
        $model->location = new Location();

        $this->firstTopPostsModel = $model;

        $account = new Account();
        $account->id = '2080433615';
        $this->firstTopPostAccountModel = $account;
    }
}
