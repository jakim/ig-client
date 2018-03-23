<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 15.03.2018
 */

namespace Jakim\Helper;

use PHPUnit\Framework\TestCase;

class JsonHelperTest extends TestCase
{

    public function testEncode()
    {
        $json = '{"true":true,"foo":"bar"}';
        $this->assertEquals($json, JsonHelper::encode(['true' => true, "foo" => "bar"]));
    }

    public function testDecode()
    {
        $json = '{"bid":25025320250253202502532025025320, "id":"1", "block":true, "name":null}';
        $arr = [
            'bid' => '25025320250253202502532025025320',
            'block' => true,
            'name' => null,
            'id' => 1,
        ];
        $this->assertEquals($arr, JsonHelper::decode($json));
    }
}
