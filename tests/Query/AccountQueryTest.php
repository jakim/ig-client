<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 15.03.2018
 */

namespace Jakim\Query;

use Generator;
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

    protected $mediaFirstModel;

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

    /**
     * @expectedException \Jakim\Exception\RestrictedProfileException
     */
    public function testFindOneRestricted()
    {
        $accountDetails = file_get_contents(__DIR__ . '/../_data/account_details_restricted.html');

        $query = new AccountQuery($this->httpClient([$accountDetails]));
        $query->findOne('bacardiusa');
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

        $this->assertInstanceOf(Generator::class, $posts);

        $i = 0;
        while ($posts->valid()) {
            $this->assertInstanceOf(Post::class, $posts->current());
            $posts->next();
            $i++;
        }
        $this->assertEquals(2, $i);

        $query = new AccountQuery($this->httpClient([$this->accountDetails]));
        $posts = $query->findLastPosts('instagram', 2);
        $this->assertEquals($this->mediaFirstModel, $posts->current());
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
        $model->id = '1893729035025305076';
        $model->caption = 'Tap the link in our bio for details on the CrossFit Anatomy Online Course.';
        $model->shortcode = 'BpH4IYhhs30';
        $model->takenAt = 1539970114;
        $model->comments = 17;
        $model->likes = 880;
        $model->isVideo = false;
        $model->typename = 'GraphImage';
        $model->url = 'https://instagram.fphx1-3.fna.fbcdn.net/vp/4cba95fd75eafd96922603d5b238c1a0/5C87E45B/t51.2885-15/e35/43269938_1925477977548595_6860073546614294782_n.jpg';
        $this->mediaFirstModel = $model;
    }

}
