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
    public function findOne(string $name): Tag
    {
        $url = Endpoint::exploreTags($name);
        $mapper = new ExploreTags();

        $res = $this->httpClient->get($url);
        $content = $res->getBody()->getContents();

        $data = JsonHelper::decode($content);
        $data = $mapper->normalizeData(Tag::class, $data);

        return $mapper->populate(Tag::class, $data);
    }

}