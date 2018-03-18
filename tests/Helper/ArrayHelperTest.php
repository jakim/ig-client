<?php
/**
 * Created for IG Client.
 * User: jakim <pawel@jakimowski.info>
 * Date: 17.03.2018
 */

namespace Jakim\Helper;

use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{

    public function testGetColumn()
    {
        $arr = [
            ['col' => 'val1'],
            ['col' => 'val2'],
        ];

        $col = ArrayHelper::getColumn($arr, 'col');
        $this->assertEquals(['val1', 'val2'], $col);
    }

    public function testGetValue()
    {
        $val = ArrayHelper::getValue(['0' => 'test'], '0');
        $this->assertEquals('test', $val);

        $val = ArrayHelper::getValue(['0' => ['test' => 'test1']], '0.test');
        $this->assertEquals('test1', $val);

        $val = ArrayHelper::getValue(['0' => ['test' => ['test1' => 'test2']]], '0.test.test1');
        $this->assertEquals('test2', $val);

        $val = ArrayHelper::getValue(['0' => ['test' => 'test1']], '0.test3', 'def');
        $this->assertEquals('def', $val);

        $obj = new \stdClass();
        $obj->foo = 'bar';
        $val = ArrayHelper::getValue($obj, 'foo');
        $this->assertEquals($obj->foo, $val);

        $val = ArrayHelper::getValue(['foo' => 'bar'], function () {
            return 'bar1';
        });
        $this->assertEquals('bar1', $val);
    }
}
