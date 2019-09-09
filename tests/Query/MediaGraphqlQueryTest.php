<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 09/09/2019
 */

namespace Jakim\Query;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jakim\Mapper\EdgeMedia;
use Jakim\Model\Account;
use Jakim\Model\MediaCollection;
use Jakim\Model\Post;
use PHPUnit\Framework\TestCase;

class MediaGraphqlQueryTest extends TestCase
{
    protected $queryTagData;
    protected $queryAccountData;

    /**
     * @var \Jakim\Model\Post
     */
    protected $tagFirstMediaModel;

    /**
     * @var \Jakim\Model\Post
     */
    protected $accountFirstMediaModel;

    public function testFindAccountMedia()
    {
        $edgeMedia = new EdgeMedia(EdgeMedia::GRAPHQL_ACCOUNT_MEDIA_ENVELOPE);
        $query = new MediaGraphqlQuery($this->httpClient([$this->queryAccountData]), $edgeMedia);
        $mediaCollection = $query->findMedia('query_hash', [
            'id' => '666',
            'first' => '',
            'after' => 'after',
        ]);

        $this->assertInstanceOf(MediaCollection::class, $mediaCollection);
        $this->assertEquals(6021, $mediaCollection->count);
        $this->assertTrue($mediaCollection->pageInfo->hasNextPage);
        $this->assertEquals($mediaCollection->pageInfo->endCursor, 'QVFENGdzaFpRbHg5dXBYWmo2MGMtQnB6SFZkSl8wVjRkVUVnRFNPaXVpd01RckRINXJMdlNGQm5Id0pEX1FtQW82YVl0YVlBZTZDdWZteFVzY0lpWUJtNQ==');
        $this->assertNotNull($mediaCollection->posts);

        $this->assertEquals($this->accountFirstMediaModel, $mediaCollection->posts['0']);
    }

    public function testFindTagMedia()
    {
        $edgeMedia = new EdgeMedia(EdgeMedia::GRAPHQL_HASHTAG_MEDIA_ENVELOPE);
        $query = new MediaGraphqlQuery($this->httpClient([$this->queryTagData]), $edgeMedia);
        $mediaCollection = $query->findMedia('query_hash', [
            'tag_name' => 'ig',
            'first' => '',
            'after' => 'after',
        ]);

        $this->assertInstanceOf(MediaCollection::class, $mediaCollection);
        $this->assertNotNull($mediaCollection->count);
        $this->assertNotNull($mediaCollection->pageInfo);
        $this->assertNotNull($mediaCollection->posts);

        $this->assertEquals($this->tagFirstMediaModel, $mediaCollection->posts['0']);

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
        $this->queryTagData = file_get_contents(__DIR__ . '/../_data/graphql_hashtag.json');

        $post1 = new Post();
        $post1->id = '2127078245745440621';
        $post1->shortcode = 'B2E5mUPHjdt';
        $post1->url = 'https://scontent-waw1-1.cdninstagram.com/vp/0f75df392a280651b8e35c717c86db98/5E0FC6BE/t51.2885-15/e35/67471033_169971410837368_946160285773345134_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com&_nc_cat=104';
        $post1->likes = 70;
        $post1->comments = 3;
        $post1->typename = 'GraphImage'; //GraphImage,GraphVideo,GraphSidecar
        $post1->isVideo = false;
        $post1->caption = 'We should be each other\'s number one fans.';
        $post1->accessibilityCaption = 'Obraz mo';
        $post1->takenAt = 1567787508;

        $account1 = new Account();
        $account1->id = '5824900270';
        $post1->account = $account1;

        $this->tagFirstMediaModel = $post1;

        $this->queryAccountData = file_get_contents(__DIR__ . '/../_data/graphql_account.json');
        $post2 = new Post();
        $post2->id = '2117702805707584453';
        $post2->shortcode = 'B1jl3tSDHPF';
        $post2->url = 'https://scontent-waw1-1.cdninstagram.com/vp/d7c69153979d6c90e574963174dc5967/5D78DFCC/t51.2885-15/e35/p1080x1080/66708798_379985906232257_2849883943676349836_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com&_nc_cat=1';
        $post2->likes = 503239;
        $post2->comments = 4219;
        $post2->typename = 'GraphVideo'; //GraphImage,GraphVideo,GraphSidecar
        $post2->isVideo = true;
        $post2->videoUrl = 'https://scontent.cdninstagram.com/v/t50.16885-16/10000000_2350412511710105_2646889128005823587_n.mp4?_nc_ht=scontent.cdninstagram.com&oe=5D7881B6&oh=b920d5de483d4cde368c848633da9c63';
        $post2->videoViews = 3360508;
        $post2->caption = 'Falconry is an all-consuming profession';
        $post2->takenAt = 1566670140;

        $account2 = new Account();
        $account2->id = '25025320';
        $account2->username = 'instagram';
        $post2->account = $account2;

        $this->accountFirstMediaModel = $post2;
    }
}
