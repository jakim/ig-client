<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 15.03.2018
 */

namespace Query;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jakim\Model\Account;
use Jakim\Model\Post;
use Jakim\Query\AccountQuery;
use PHPUnit\Framework\TestCase;

class AccountQueryTest extends TestCase
{
    protected $accountData;
    protected $accountModel;
    protected $lastPostModel;

    protected $accountMediaPage1Data;
    protected $accountMediaPage2Data;
    protected $mediaFirstModel;
    protected $mediaLastModel;

    public function testFindOne()
    {
        $query = new AccountQuery($this->httpClient([$this->accountData]));
        $account = $query->findOne('instagram');

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($this->accountModel, $account);
    }

    public function testFindLastPosts()
    {
        $query = new AccountQuery($this->httpClient([$this->accountData]));
        $posts = $query->findLastPosts('instagram', 2);

        $this->assertInstanceOf(\Generator::class, $posts);

        $i = 0;
        while ($posts->valid()) {
            $this->assertInstanceOf(Post::class, $posts->current());
            $posts->next();
            $i++;
        }
        $this->assertEquals(2, $i);

        $query = new AccountQuery($this->httpClient([$this->accountData]));
        $posts = $query->findLastPosts('instagram', 2);
        $this->assertEquals($this->lastPostModel, $posts->current());
    }

    public function testFindPosts()
    {
        // more then 12 posts
        $httpClient = $this->httpClient([
            $this->accountData,
            $this->accountMediaPage1Data,
            $this->accountMediaPage2Data,
        ]);
        $query = new AccountQuery($httpClient);
        $query->postsPerPage = 12;

        $generator = $query->findPosts('instagram', 23);
        $this->assertInstanceOf(\Generator::class, $generator);

        $posts = [];
        foreach ($generator as $post) {
            $this->assertInstanceOf(Post::class, $post);
            $posts[] = $post;
        }
        $this->assertEquals($this->mediaFirstModel, $posts['0']);
        $this->assertEquals($this->mediaLastModel, end($posts));
        $this->assertCount(23, $posts);

        // less then 12 posts
        $httpClient = $this->httpClient([
            $this->accountData,
        ]);
        $query = new AccountQuery($httpClient);
        $query->postsPerPage = 12;

        $generator = $query->findPosts('instagram', 10);
        $this->assertInstanceOf(\Generator::class, $generator);

        $posts = [];
        foreach ($generator as $post) {
            $this->assertInstanceOf(Post::class, $post);
            $posts[] = $post;
        }
        $this->assertCount(10, $posts);

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
        $this->accountData = file_get_contents(__DIR__ . '/../_data/account_details.html');
        $this->accountMediaPage1Data = file_get_contents(__DIR__ . '/../_data/account_media_query_id_page_1.json');
        $this->accountMediaPage2Data = file_get_contents(__DIR__ . '/../_data/account_media_query_id_page_2.json');

        $model = new Account();
        $model->username = 'instagram';
        $model->id = '25025320';
        $model->biography = 'Discovering — and telling — stories from around the world. Founded in 2010 by @kevin and @mikeyk.';
        $model->externalUrl = null;
        $model->followedBy = 234321415;
        $model->follows = 184;
        $model->fullName = 'Instagram';
        $model->isPrivate = false;
        $model->media = 5185;
        $model->profilePicUrl = 'https://scontent-waw1-1.cdninstagram.com/vp/893534d61bdc5ea6911593d3ee0a1922/5B6363AB/t51.2885-19/s320x320/14719833_310540259320655_1605122788543168512_a.jpg';
        $this->accountModel = $model;

        $model = new Post();
        $model->id = '1735046366533360191';
        $model->caption = 'Video by @paulrabil';
        $model->shortcode = 'BgUH3pKDZo_';
        $model->takenAt = 1521053724;
        $model->comments = 3431;
        $model->likes = 196733;
        $model->isVideo = true;
        $model->url = 'https://scontent-waw1-1.cdninstagram.com/vp/21b0b63bca8c47bb6e2f264dc6da2e5c/5AAC5C7C/t51.2885-15/e15/28763547_142668606559355_4973623294713397248_n.jpg';
        $this->lastPostModel = $model;

        $model = new Post();
        $model->id = '1736561353911177111';
        $model->caption = 'Featured photo by @frederic_vasquez';
        $model->shortcode = 'BgZgVnGjA-X';
        $model->takenAt = 1521234266;
        $model->comments = 6437;
        $model->likes = 688335;
        $model->isVideo = false;
        $model->url = 'https://scontent-waw1-1.cdninstagram.com/vp/f8ca367e3e2d36867674625a085a2506/5B452EC2/t51.2885-15/e35/28754222_155982595090677_177797884979183616_n.jpg';
        $this->mediaFirstModel = $model;

        $model = new Post();
        $model->id = '1726537492972684257';
        $model->caption = 'Video by @negrito.the.kitcat';
        $model->shortcode = 'Bf15LPIB8Ph';
        $model->takenAt = 1520039394;
        $model->comments = 17079;
        $model->likes = 854686;
        $model->isVideo = true;
        $model->url = 'https://scontent-waw1-1.cdninstagram.com/vp/af659e60dd4d09ae8b929a1cc0e884a6/5AB093B1/t51.2885-15/e15/28430338_1808422849181442_4008413907008880640_n.jpg';
        $this->mediaLastModel = $model;


    }

}
