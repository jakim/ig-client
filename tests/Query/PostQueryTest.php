<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 23.03.2018
 */

namespace Jakim\Query;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Jakim\Mapper\MediaDetails;
use Jakim\Model\Account;
use Jakim\Model\Location;
use Jakim\Model\Post;
use PHPUnit\Framework\TestCase;

class PostQueryTest extends TestCase
{
    protected $postData;
    protected $postModel;

    public function testFindOneByShortcode()
    {
        $query = new PostQuery($this->httpClient([$this->postData]), new MediaDetails());
        $post = $query->findOneByShortcode('instagram', true);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($this->postModel, $post);
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
        $this->postData = file_get_contents(__DIR__ . '/../_data/post_details.json');

        $post = new Post();
        $post->id = '2119815580323176027';
        $post->shortcode = 'B1rGQn-gF5b';
        $post->url = 'https://scontent-waw1-1.cdninstagram.com/vp/ec8b23b10fa286c8b8bb6163afffb690/5E144047/t51.2885-15/e35/67474320_167092097792849_5769507534716184786_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com';
        $post->isVideo = false;
        $post->caption = '“Everything I’m doing is some reflection or study of myself,” says Sunny Cobb (@sun_muun). “The rainbow is directly tied to my identity as a queer person, and the only consistent part of my approach to makeup is that it’s about me.” ☁️🌈☁️⁣';
        $post->comments = 12497;
        $post->takenAt = 1566921731;
        $post->likes = 450474;
        $post->typename = 'GraphImage';
        $post->accessibilityCaption = 'Close-up of a face with sky, cloud and rainbow makeup.';

        $account = new Account();
        $account->id = '25025320';
        $account->profilePicUrl = 'https://scontent-waw1-1.cdninstagram.com/vp/858a84d5e34497c7cb431cc4577df3ed/5E03515D/t51.2885-19/s150x150/59381178_2348911458724961_5863612957363011584_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com';
        $account->username = 'instagram';
        $account->fullName = 'Instagram';
        $account->isPrivate = false;
        $post->account = $account;

        $location = new Location();
        $location->id = '113226037';
        $location->hasPublicPage = true;
        $location->name = 'Louisville, Kentucky';
        $location->slug = 'louisville-kentucky';
        $location->addressJson = "{\"street_address\": \"\", \"zip_code\": \"major: 402xx, minor: 400xx, 401xx\", \"city_name\": \"Louisville, Kentucky\", \"region_name\": \"\", \"country_code\": \"US\", \"exact_city_match\": true, \"exact_region_match\": false, \"exact_country_match\": false}";
        $post->location = $location;

        $sponsor = new Account();
        $sponsor->id = '208';
        $sponsor->username = 'john_doe';
        $post->sponsor = $sponsor;

        $tagged = new Account();
        $tagged->fullName = 'Sunny';
        $tagged->id = '4508251517';
        $tagged->isVerified = false;
        $tagged->profilePicUrl = 'https://scontent-waw1-1.cdninstagram.com/vp/07243757f60e454b8e30c2767ac7d930/5E13C205/t51.2885-19/s150x150/58453733_815524285500569_7092081128525266944_n.jpg?_nc_ht=scontent-waw1-1.cdninstagram.com';
        $tagged->username = 'sun_muun';
        $post->tagged[] = $tagged;

        $this->postModel = $post;
    }
}
