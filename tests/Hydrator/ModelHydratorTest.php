<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 01/05/2020
 */

namespace Hydrator;

use Jakim\Hydrator\ModelHydrator;
use Jakim\Map\MapInterface;
use Jakim\Model\Account;
use Jakim\Model\MediaCollection;
use Jakim\Model\PageInfo;
use Jakim\Model\Post;
use PHPUnit\Framework\TestCase;

class ModelHydratorTest extends TestCase
{
    public function testModel()
    {
        $data = [
            'test1' => [
                'test2' => [
                    'test3' => 'username_ok',
                    'test4' => [
                        [
                            'test5' => 'full_name_ok',
                        ],
                    ],
                ],
            ],
        ];

        $hydrator = new ModelHydrator([
            MapInterface::ENVELOPE => 'test1',
            MapInterface::PROPERTIES => [
                'username' => 'test2.test3',
                'fullName' => 'test2.test4.0.test5',
            ],
        ]);

        $model = new Account();
        $model->isPrivate = false;

        /** @var Account $model */
        $model = $hydrator->hydrate($model, $data);

        $this->assertEquals('username_ok', $model->username);
        $this->assertEquals('full_name_ok', $model->fullName);
        $this->assertFalse($model->isPrivate);
        $this->assertNull($model->isBusiness);
    }

    public function testSingleRelation()
    {
        $data = [
            'test1' => [
                'test2' => [
                    'test3' => 'test_hasNextPage',
                    'test4' => 'test_endCursor',
                ],
            ],
        ];

        $hydrator = new ModelHydrator([
            MapInterface::ENVELOPE => 'test1',
            MapInterface::PROPERTIES => [
                'pageInfo' => [
                    MapInterface::ENVELOPE => 'test2',
                    MapInterface::MODEL => PageInfo::class,
                    MapInterface::PROPERTIES => [
                        'hasNextPage' => 'test3',
                        'endCursor' => 'test4',
                    ],
                ],
            ],
        ]);

        $model = new MediaCollection();

        /** @var MediaCollection $model */
        $model = $hydrator->hydrate($model, $data);

        $this->assertEquals('test_endCursor', $model->pageInfo->endCursor);
        $this->assertEquals('test_hasNextPage', $model->pageInfo->hasNextPage);
    }

    public function testMultipleRelation()
    {
        $data = [
            'test1' => [
                'test2_1' => 123,
                'test2_2' => [
                    [
                        'test3' => 'test_shortcode',
                        'test4' => 'test_typename',
                    ],
                ],
            ],
        ];

        $hydrator = new ModelHydrator([
            MapInterface::ENVELOPE => 'test1',
            MapInterface::PROPERTIES => [
                'count' => 'test2_1',
                'posts' => [
                    MapInterface::MULTIPLE => true,
                    MapInterface::ENVELOPE => 'test2_2',
                    MapInterface::MODEL => Post::class,
                    MapInterface::PROPERTIES => [
                        'shortcode' => 'test3',
                        'typename' => 'test4',
                    ],
                ],
            ],
        ]);

        $model = new MediaCollection();

        /** @var MediaCollection $model */
        $model = $hydrator->hydrate($model, $data);

        $this->assertEquals(123, $model->count);
        $this->assertEquals('test_shortcode', $model->posts['0']->shortcode);
        $this->assertEquals('test_typename', $model->posts['0']->typename);
    }
}
