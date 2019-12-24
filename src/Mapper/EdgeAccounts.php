<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 24/12/2019
 */

namespace Jakim\Mapper;


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
            'class' => AccountsCollection::class,
            'envelope' => $this->envelope,
            'properties' => [
                'count' => 'count',
            ],
            'relations' => [
                'pageInfo' => [
                    'class' => PageInfo::class,
                    'envelope' => 'page_info',
                    'properties' => [
                        'hasNextPage' => 'has_next_page',
                        'endCursor' => 'end_cursor',
                    ],
                ],
                'accounts' => [
                    'multiple' => true,
                    'class' => Account::class,
                    'envelope' => 'edges',
                    'properties' => [
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