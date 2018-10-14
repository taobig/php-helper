<?php


class ArrayHelperTest extends TestCase
{

    public function testSplit()
    {
        $result = \taobig\helpers\ArrayHelper::split([1, 2, 3], 1);
        $this->assertSame(true, is_array($result));
        $this->assertSame(3, count($result));
        $this->assertSame(1, count($result[0]));
        $this->assertSame(1, $result[0][0]);
        $this->assertSame(1, count($result[1]));
        $this->assertSame(2, $result[1][0]);
        $this->assertSame(1, count($result[2]));
        $this->assertSame(3, $result[2][0]);


        $result = \taobig\helpers\ArrayHelper::split([1, 2, 3], 2);
        $this->assertSame(true, is_array($result));
        $this->assertSame(2, count($result));
        $this->assertSame(2, count($result[0]));
        $this->assertSame(1, $result[0][0]);
        $this->assertSame(2, $result[0][1]);
        $this->assertSame(1, count($result[1]));
        $this->assertSame(3, $result[1][0]);


        $result = \taobig\helpers\ArrayHelper::split([1, 2, 3], 3);
        $this->assertSame(true, is_array($result));
        $this->assertSame(1, count($result));
        $this->assertSame(3, count($result[0]));
        $this->assertSame(1, $result[0][0]);
        $this->assertSame(2, $result[0][1]);
        $this->assertSame(3, $result[0][2]);

        $result = \taobig\helpers\ArrayHelper::split([1, 2, 3], 4);
        $this->assertSame(true, is_array($result));
        $this->assertSame(1, count($result));
        $this->assertSame(1, $result[0][0]);
        $this->assertSame(2, $result[0][1]);
        $this->assertSame(3, $result[0][2]);


        $result = \taobig\helpers\ArrayHelper::split([1, 2, 3], 0);
        $this->assertSame(true, is_array($result));
        $this->assertSame(0, count($result));;
    }


}
