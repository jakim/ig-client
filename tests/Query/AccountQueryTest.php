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
        $model->externalUrl = 'https://www.menshealth.com/entertainment/a23621596/arnold-schwarzenegger-bodybuilding-interview/';
        $model->followedBy = 15297107;
        $model->follows = 57;
        $model->fullName = 'Arnold Schwarzenegger';
        $model->isPrivate = false;
        $model->media = 629;
        $model->profilePicUrl = 'https://instagram.fphx1-3.fna.fbcdn.net/vp/f0fa95c44a90f043dae6dd2ece9cbbef/5C66CCFF/t51.2885-19/12964988_243704659317412_177347800_a.jpg';
        $model->isVerified = true;
        $model->isBusiness = null;
        $model->businessCategory = null;
        $this->accountInfoModel = $model;

        $model = new Account();
        $model->username = 'crossfit';
        $model->id = '255768340';
        $model->biography = 'The official Instagram page for #CrossFit. Links';
        $model->externalUrl = 'https://linktr.ee/crossfit';
        $model->followedBy = 2600232;
        $model->follows = 1318;
        $model->fullName = 'CrossFit';
        $model->isPrivate = false;
        $model->media = 9402;
        $model->profilePicUrl = 'https://instagram.fphx1-3.fna.fbcdn.net/vp/6ce20f16cd5d48cd576de10301235c03/5C87C219/t51.2885-19/s320x320/10723747_996967810364381_270381264_a.jpg';
        $model->isVerified = true;
        $model->isBusiness = true;
        $model->businessCategory = 'Publishers';
        $this->accountDetailsModel = $model;

        $model = new Post();
        $model->id = '1892305037402033264';
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
