<?php

use taobig\helpers\ArrayHelper;

class ArrayHelperTest extends TestCase
{

    public function testSplit()
    {
        $result = ArrayHelper::split([1, 2, 3], 1);
        $this->assertSame(true, is_array($result));
        $this->assertSame(3, count($result));
        $this->assertSame(1, count($result[0]));
        $this->assertSame(1, $result[0][0]);
        $this->assertSame(1, count($result[1]));
        $this->assertSame(2, $result[1][0]);
        $this->assertSame(1, count($result[2]));
        $this->assertSame(3, $result[2][0]);


        $result = ArrayHelper::split([1, 2, 3], 2);
        $this->assertSame(true, is_array($result));
        $this->assertSame(2, count($result));
        $this->assertSame(2, count($result[0]));
        $this->assertSame(1, $result[0][0]);
        $this->assertSame(2, $result[0][1]);
        $this->assertSame(1, count($result[1]));
        $this->assertSame(3, $result[1][0]);


        $result = ArrayHelper::split([1, 2, 3], 3);
        $this->assertSame(true, is_array($result));
        $this->assertSame(1, count($result));
        $this->assertSame(3, count($result[0]));
        $this->assertSame(1, $result[0][0]);
        $this->assertSame(2, $result[0][1]);
        $this->assertSame(3, $result[0][2]);

        $result = ArrayHelper::split([1, 2, 3], 4);
        $this->assertSame(true, is_array($result));
        $this->assertSame(1, count($result));
        $this->assertSame(1, $result[0][0]);
        $this->assertSame(2, $result[0][1]);
        $this->assertSame(3, $result[0][2]);


        $result = ArrayHelper::split([1, 2, 3], 0);
        $this->assertSame(true, is_array($result));
        $this->assertSame(0, count($result));;
    }


    public function testGroupBy()
    {
        $arr = [
            ['a' => 'a', 'b' => 'b'],
            ['a' => 'aa', 'b' => 'b'],
            ['a' => 'a', 'b' => 'bb'],
            ['a' => null, 'b' => 'bb'],
            ['a' => false, 'b' => 'bb'],
            ['a' => 11.11, 'b' => 'bb'],
            ['a' => "11.11", 'b' => 'bbb'],
            ['a' => "false", 'b' => 'bbb'],
        ];
        $result = ArrayHelper::groupBy($arr, 'a');
        $this->assertSame(true, is_array($result));
        $this->assertSame(4, count($result));
        $this->assertSame(1, count($result['aa']));
        $this->assertSame(1, count($result['11.11']));
        $this->assertSame(1, count($result['false']));
        $this->assertSame(false, array_key_exists('b', $result));
        $this->assertSame(false, array_key_exists('bb', $result));
    }

}
