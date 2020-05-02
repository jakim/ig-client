<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 24/12/2019
 */

namespace Jakim\Map;


use Jakim\Base\Mapper;
use Jakim\Model\Account;
use Jakim\Model\AccountsCollection;
use Jakim\Model\PageInfo;

class EdgeAccounts extends Mapper
{
    const FOLLOWERS_ENVELOPE = 'data.user.edge_followed_by';

    protected $envelope = self::FOLLOWERS_ENVELOPE;

    public function __construct(string $envelope = self::FOLLOWERS_ENVELOPE)
    {
        $this->setEnvelope($envelope);
    }

    public function setEnvelope($key)
    {
        $this->envelope = $key;
    }

    /**
     * @inheritDoc
     */
    public function config(): array
    {
        return [
            MapInterface::MODEL => AccountsCollection::class,
            MapInterface::ENVELOPE => $this->envelope,
            MapInterface::PROPERTIES => [
                'count' => 'count',
                'pageInfo' => [
                    MapInterface::MODEL => PageInfo::class,
                    MapInterface::ENVELOPE => 'page_info',
                    MapInterface::PROPERTIES => [
                        'hasNextPage' => 'has_next_page',
                        'endCursor' => 'end_cursor',
                    ],
                ],
                'accounts' => [
                    MapInterface::MULTIPLE => true,
                    MapInterface::MODEL => Account::class,
                    MapInterface::ENVELOPE => 'edges',
                    MapInterface::PROPERTIES => [
                        'id' => 'node.id',
                        'username' => 'node.username',
                        'fullName' => 'node.full_name',
                        'profilePicUrl' => 'node.profile_pic_url',
                        'isPrivate' => 'node.is_private',
                        'isVerified' => 'node.is_verified',
                    ],
                ],
            ],
        ];
    }
}