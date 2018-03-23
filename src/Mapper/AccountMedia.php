<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 17.03.2018
 */

namespace Jakim\Mapper;


use Jakim\Helper\ArrayHelper;
use Jakim\Model\Post;

class AccountMedia extends AccountDetails
{
    public function nextPage(array $data)
    {
        return ArrayHelper::getValue($data, 'data.user.edge_owner_to_timeline_media.page_info.end_cursor', null);
    }

    protected function map(): array
    {
        $map = ArrayHelper::getValue(parent::map(), Post::class);
        $map['envelope'] = 'data.user.edge_owner_to_timeline_media.edges';

        return [
            Post::class => $map,
        ];
    }
}