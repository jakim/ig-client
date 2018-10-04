<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 15.03.2018
 */

namespace Jakim\Query;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jakim\Model\Account;
use Jakim\Model\Post;
use PHPUnit\Framework\TestCase;

class AccountQueryTest extends TestCase
{
    protected $accountInfo;
    protected $accountInfoModel;
    protected $accountDetails;
    protected $accountDetailsModel;
    protected $lastPostModel;

    protected $accountMediaPage1Data;
    protected $accountMediaPage2Data;
    protected $mediaFirstModel;
    protected $mediaLastModel;

    public function testFindOne()
    {
        $query = new AccountQuery($this->httpClient([$this->accountDetails]));
        $account = $query->findOne('instagram');

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($this->accountDetailsModel, $account);

        $query = new AccountQuery($this->httpClient([$this->accountInfo]));
        $account = $query->findOne('198945880');

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($this->accountInfoModel, $account);
    }

    public function testFindOneByUsername()
    {
        $query = new AccountQuery($this->httpClient([$this->accountDetails]));
        $account = $query->findOneByUsername('instagram');

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($this->accountDetailsModel, $account);
    }

    public function testFindOneById()
    {
        $query = new AccountQuery($this->httpClient([$this->accountInfo]));
        $account = $query->findOneById('198945880');

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($this->accountInfoModel, $account);
    }

    public function testFindLastPosts()
    {
        $query = new AccountQuery($this->httpClient([$this->accountDetails]));
        $posts = $query->findLastPosts('instagram', 2);

        $this->assertInstanceOf(\Generator::class, $posts);

        $i = 0;
        while ($posts->valid()) {
            $this->assertInstanceOf(Post::class, $posts->current());
            $posts->next();
            $i++;
        }
        $this->assertEquals(2, $i);

        $query = new AccountQuery($this->httpClient([$this->accountDetails]));
        $posts = $query->findLastPosts('instagram', 2);
        $this->assertEquals($this->lastPostModel, $posts->current());
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
        $this->accountInfo = file_get_contents(__DIR__ . '/../_data/account_info.json');
        $this->accountDetails = file_get_contents(__DIR__ . '/../_data/account_details.html');
        $this->accountMediaPage1Data = file_get_contents(__DIR__ . '/../_data/account_media_query_id_page_1.json');
        $this->accountMediaPage2Data = file_get_contents(__DIR__ . '/../_data/account_media_query_id_page_2.json');

        $model = new Account();
        $model->username = 'schwarzenegger';
        $model->id = '198945880';
        $model->biography = 'Former Mr. Olympia, Conan, Terminator, and Governor of California. I killed the Predator. I told you I\'d be back.';
        $model->externalUrl = 'http://omaze.com/Arnold';
        $model->followedBy = 13960923;
        $model->follows = 43;
        $model->fullName = 'Arnold Schwarzenegger';
        $model->isPrivate = false;
        $model->media = 581;
        $model->profilePicUrl = 'https://scontent-waw1-1.cdninstagram.com/vp/3403233dbc8d59f3bd1f0527750811f1/5B51F1FF/t51.2885-19/12964988_243704659317412_177347800_a.jpg';
        $this->accountInfoModel = $model;

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
        $this->accountDetailsModel = $model;

        $model = new Post();
        $model->id = '1878604801479471730';
        $model->caption = 'If you really do what you love';
        $model->shortcode = 'BoSJR0ZhvJy';
        $model->takenAt = 1538167222;
        $model->comments = 4440;
        $model->likes = 439985;
        $model->isVideo = true;
        $model->videoViews = 3933378;
        $model->url = 'https://scontent-waw1-1.cdninstagram.com/vp/49024ded4252035fc732a1c65f1c876f/5BB8A70D/t51.2885-15/e15/41949781_268245540489087_7381056827079503311_n.jpg';
        $model->typename = 'GraphVideo';
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
