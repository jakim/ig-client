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
use Jakim\Model\Location;
use Jakim\Model\Post;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
use PHPUnit\Framework\TestCase;

class AccountQueryTest extends TestCase
{
    protected $accountInfo;
    protected $accountInfoModel;
    protected $accountDetails;
    protected $accountDetailsModel;

    protected $mediaFirstPostModel;
    protected $mediaFirstPostLocationModel;

    public function testFindOne()
    {
        $query = new AccountQuery($this->httpClient([$this->accountDetails]));
        $account = $query->findOne('instagram');

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($this->accountDetailsModel, $account);
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
        $this->assertEquals($this->mediaFirstPostModel, $posts->current());

        $query = new AccountQuery($this->httpClient([$this->accountDetails]));
        $posts = $query->findLastPosts('instagram', 2, true);
        $this->mediaFirstPostModel->location = $this->mediaFirstPostLocationModel;
        $this->assertEquals($this->mediaFirstPostModel, $posts->current());
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
        $model->username = 'instagram';
        $model->profilePicUrl = 'https://scontent-waw1-1.cdninstagram.com/vp/a76a53c7fc27a4025c9ba80fd3627732/5DDBC45D/t51.2885-19/s150x150/59381178_2348911458724961_5863612957363011584_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com';
        $this->accountInfoModel = $model;

        $model = new Account();
        $model->id = '25025320';
        $model->username = 'instagram';
        $model->profilePicUrl = 'https://scontent-waw1-1.cdninstagram.com/vp/2cd4ceda40e5e0d2bd1acd51709271fb/5DC75A25/t51.2885-19/s320x320/59381178_2348911458724961_5863612957363011584_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com';
        $model->fullName = 'Instagram';
        $model->biography = 'Bringing you closer to the people and things you love. ❤️';
        $model->externalUrl = 'https://instagram-press.com/blog/2019/07/08/our-commitment-to-lead-the-fight-against-online-bullying/';
        $model->followedBy = 308268458;
        $model->follows = 222;
        $model->media = 5953;
        $model->isPrivate = false;
        $model->isVerified = true;
        $model->isBusiness = false;
        $this->accountDetailsModel = $model;

        $model = new Post();
        $model->id = '2098222880075746713';
        $model->caption = '#HelloFrom Cenote Calavera in Tulum, Mexico.';
        $model->shortcode = 'B0eYpeygw2Z';
        $model->takenAt = 1564347680;
        $model->comments = 10441;
        $model->likes = 743410;
        $model->isVideo = false;
        $model->typename = 'GraphImage';
        $model->url = 'https://scontent-waw1-1.cdninstagram.com/vp/208c8f0aadc8de3cf8cd710fbabcf961/5DCD398C/t51.2885-15/e35/p1080x1080/66155155_1404613096371381_980496478990375238_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com';
        $this->mediaFirstPostModel = $model;

        $model = new Location();
        $model->id = '720186901467144';
        $model->hasPublicPage = true;
        $model->name = 'Cenote Calavera';
        $model->slug = 'cenote-calavera';
        $this->mediaFirstPostLocationModel = $model;
    }

}
