<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 16.03.2018
 */

namespace Jakim\Query;


use Jakim\Base\Query;
use Jakim\Helper\JsonHelper;
use jakim\ig\Endpoint;
use Jakim\Mapper\ExploreTags;
use Jakim\Model\Tag;

class TagQuery extends Query
{
    protected $findOneMapper;

    public function __construct($httpClient, ExploreTags $findOneMapper = null)
    {
        parent::__construct($httpClient);
        $this->findOneMapper = $findOneMapper ?? new ExploreTags();
    }

    public function findOne(string $name): Tag
    {
        $url = Endpoint::exploreTags($name);

        $res = $this->httpClient->get($url);
        $content = $res->getBody()->getContents();

        $data = JsonHelper::decode($content);
        $data = $this->findOneMapper->normalizeData(Tag::class, $data);

        return $this->findOneMapper->populate(Tag::class, $data);
    }

}