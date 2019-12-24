<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 24/12/2019
 */

namespace Mapper;

use Jakim\Mapper\EdgeAccounts;
use PHPUnit\Framework\TestCase;

class EdgeAccountsTest extends TestCase
{
    public function testCreateModel()
    {
        $mapper = new EdgeAccounts(EdgeAccounts::FOLLOWERS_ENVELOPE);
        $data = file_get_contents(__DIR__ . '/../_data/followers.json');
        $data = json_decode($data, true);
        $data = $mapper->getData($data, $mapper->config());
        /** @var \Jakim\Model\AccountsCollection $model */
        $model = $mapper->createModel($data, $mapper->config(), true);

        $this->assertEquals(54626, $model->count);
        $this->assertNotEmpty($model->pageInfo);
        $this->assertTrue($model->pageInfo->hasNextPage);
        $this->assertCount(24, $model->accounts);
        foreach ($model->accounts as $account) {
            $this->assertNotEmpty($account->id);
            $this->assertNotEmpty($account->username);
        }
        $this->assertEquals('8971404671', $account->id);
        $this->assertEquals('kubakuba6668', $account->username);
    }
}
