<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 14.03.2018
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use Jakim\Helper\JsonHelper;
use jakim\ig\Endpoint;
use Jakim\Mapper\AccountDetails;
use Jakim\Mapper\AccountMedia;
use Jakim\Model\Account;
use Jakim\Model\Post;

class AccountQuery extends Query
{
    const MAX_POSTS_PER_PAGE = 100;
    public $postsPerPage = 100;

    public function findOne(string $username): Account
    {
        $url = Endpoint::accountDetails($username);
        $mapper = new AccountDetails();

        $res = $this->httpClient->get($url);
        $content = $res->getBody()->getContents();

        $array = JsonHelper::decode($content);
        $data = $mapper->normalizeData(Account::class, $array);

        return $mapper->populate(Account::class, $data);
    }

    /**
     * @param string $username
     * @param int $limit Max 12, for more see findPosts()
     * @return \Generator
     *
     * @see \Jakim\Query\AccountQuery::findPosts
     */
    public function findLastPosts(string $username, int $limit = 12)
    {
        $url = Endpoint::accountDetails($username);
        $mapper = new AccountDetails();

        $res = $this->httpClient->get($url);
        $content = $res->getBody()->getContents();
        $data = JsonHelper::decode($content);

        $items = $mapper->normalizeData(Post::class, $data);

        $n = 0;
        foreach ($items as $item) {
            $model = $mapper->populate(Post::class, $item);

            yield $model;

            if (++$n >= $limit) {
                break;
            }
        }
    }

    public function findPosts(string $username, int $limit = 100)
    {
        if ($limit <= 12) {
            return $this->findLastPosts($username, $limit);
        }

        $account = $this->findOne($username);
        $mapper = new AccountMedia();

        $n = 0;
        $pages = (int)ceil($limit / ($this->postsPerPage > self::MAX_POSTS_PER_PAGE ? self::MAX_POSTS_PER_PAGE : $this->postsPerPage));
        $nextPage = '';

        for ($p = 0; $p < $pages; $p++) {
            $url = Endpoint::accountMedia($account->id, 100, [
                'variables' => ['after' => $nextPage],
            ]);

            $res = $this->httpClient->get($url);
            $content = $res->getBody()->getContents();
            $data = JsonHelper::decode($content);

            $nextPage = $mapper->nextPage($data);

            $items = $mapper->normalizeData(Post::class, $data);

            foreach ($items as $item) {
                yield $mapper->populate(Post::class, $item);

                if (++$n >= $limit) {
                    break 2;
                }
            }
            if ($nextPage === false) {
                break;
            }

        }
    }
}