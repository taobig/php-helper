<?php

declare(strict_types=1);

namespace taobig\tests\helpers\helpers;

use stdClass;
use taobig\helpers\ArrayHelper;

class ArrayHelperTest extends \TestCase
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


        $result = ArrayHelper::split(['a' => 1, 2, 3], 2, true);
        $this->assertSame(true, is_array($result));
        $this->assertSame(2, count($result));
        $this->assertSame(2, count($result[0]));
        $this->assertSame(1, $result[0]['a']);
        $this->assertSame(2, $result[0][0]);
        $this->assertSame(1, count($result[1]));
        $this->assertSame(3, $result[1][1]);


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

    public function testUnderscore2camelcase()
    {
        $arr = [
            "id" => 1,
            "user_name" => "yes",
            "test_1" => 3,
            "good_news" => [
                "user_name" => "yes",
                "test_1" => 3,
                "good_news" => [
                    "user_name" => "no",
                    "test_1" => 3,
                    "good_news" => [
                    ],
                ],
            ],
            "Happy_bird" => 1,
            "HappyBird" => 2,
        ];
        $result = ArrayHelper::underscore2camelcase($arr);
        $this->assertSame(5, count($result));
        $this->assertSame(true, isset($result['userName']));
        $this->assertSame("yes", ($result['userName']));
        $this->assertSame(true, isset($result['test1']));
        $this->assertSame(3, ($result['test1']));
        $this->assertSame(true, isset($result['goodNews']));
        $this->assertSame(true, isset($result['goodNews']['userName']));
        $this->assertSame(true, isset($result['goodNews']['goodNews']['userName']));
        $this->assertSame("no", ($result['goodNews']['goodNews']['userName']));
        $this->assertSame(1, ($result['happyBird']));

        $result = ArrayHelper::underscore2camelcase($arr, false);
        $this->assertSame(2, ($result['happyBird']));

        $o = new \StdClass();
        $o->aaa = 11;
        $arr = [
            "a" => 1,
            "aBC" => 2,
            "a_b" => 3,
            "a_b_c_d" => $o,
            "EFGH" => $o,
            "mK" => $o,
            "l_s" => $o,
        ];
        $result = ArrayHelper::underscore2camelcase($arr);

        $this->assertSame(7, count($result));
        $this->assertSame(11, ($result['eFGH']->aaa));
        $this->assertSame(11, ($result['mK']->aaa));
        $this->assertSame(11, ($result['lS']->aaa));
    }

    public function testRemoveEmptyElement()
    {
        $arr = [1, 2, 3, 0, '', 99, ['test']];
        $result = ArrayHelper::removeEmptyElement($arr);
        $this->assertSame(true, is_array($result));
        $this->assertSame(5, count($result));
        $this->assertSame(true, array_key_exists(2, $result));
        $this->assertSame(false, array_key_exists(3, $result));
        $this->assertSame(false, array_key_exists(4, $result));
        $this->assertSame(true, array_key_exists(5, $result));

        $result = ArrayHelper::removeEmptyElement($arr, true);
        $this->assertSame(true, is_array($result));
        $this->assertSame(5, count($result));
        $this->assertSame(true, array_key_exists(2, $result));
        $this->assertSame(true, array_key_exists(3, $result));
        $this->assertSame(true, array_key_exists(4, $result));
        $this->assertSame(false, array_key_exists(5, $result));
    }

    public function testRemoveSpecifiedElement()
    {
        $arr = [1, 2, 3, null, 4];
        $result = ArrayHelper::removeSpecifiedElement($arr);
        $this->assertSame(true, is_array($result));
        $this->assertSame(4, count($result));
        $this->assertSame(true, array_key_exists(0, $result));
        $this->assertSame(true, array_key_exists(1, $result));
        $this->assertSame(true, array_key_exists(2, $result));
        $this->assertSame(false, array_key_exists(3, $result));
        $this->assertSame(true, array_key_exists(4, $result));

        $arr = [1, 2, 3, 3, 4];
        $result = ArrayHelper::removeSpecifiedElement($arr, 3);
        $this->assertSame(true, is_array($result));
        $this->assertSame(3, count($result));
        $this->assertSame(true, array_key_exists(0, $result));
        $this->assertSame(true, array_key_exists(1, $result));
        $this->assertSame(false, array_key_exists(2, $result));
        $this->assertSame(false, array_key_exists(3, $result));
        $this->assertSame(true, array_key_exists(4, $result));

        $arr = [1, 2, 3, 3, 4];
        $result = ArrayHelper::removeSpecifiedElement($arr, 3, true);
        $this->assertSame(true, is_array($result));
        $this->assertSame(3, count($result));
        $this->assertSame(true, array_key_exists(0, $result));
        $this->assertSame(true, array_key_exists(1, $result));
        $this->assertSame(false, array_key_exists(2, $result));
        $this->assertSame(false, array_key_exists(3, $result));
        $this->assertSame(true, array_key_exists(4, $result));

        $arr = [1, 2, 3, '3', 4];
        $result = ArrayHelper::removeSpecifiedElement($arr, 3, true);
        $this->assertSame(true, is_array($result));
        $this->assertSame(4, count($result));
        $this->assertSame(true, array_key_exists(0, $result));
        $this->assertSame(true, array_key_exists(1, $result));
        $this->assertSame(false, array_key_exists(2, $result));
        $this->assertSame(true, array_key_exists(3, $result));
        $this->assertSame(true, array_key_exists(4, $result));

        $arr = [1, 2, 3, '3', 4];
        $result = ArrayHelper::removeSpecifiedElement($arr, 3, true, true);
        $this->assertSame(true, is_array($result));
        $this->assertSame(4, count($result));
        $this->assertSame(true, array_key_exists(0, $result));
        $this->assertSame(true, array_key_exists(1, $result));
        $this->assertSame(true, array_key_exists(2, $result));
        $this->assertSame(true, array_key_exists(3, $result));
        $this->assertSame(false, array_key_exists(4, $result));
    }

    public function testIsAssocArray()
    {
        $arr = [];
        $this->assertSame(true, ArrayHelper::isAssocArray($arr));

        $arr = ['a' => 1, 2, 3, 4];
        $this->assertSame(true, ArrayHelper::isAssocArray($arr));

        $arr = [1, 2, 3, 4];
        $this->assertSame(false, ArrayHelper::isAssocArray($arr));
    }


    public function testIsIndexedArray()
    {
        $arr = [];
        $this->assertSame(true, ArrayHelper::isIndexedArray($arr));

        $arr = [1, 2, 3, 4];
        $this->assertSame(true, ArrayHelper::isIndexedArray($arr));

        $arr = ['a' => 1, 2, 3, 4];
        $this->assertSame(false, ArrayHelper::isIndexedArray($arr));

        $arr = ['0' => 1, 2, 3, 4];
        $this->assertSame(true, ArrayHelper::isIndexedArray($arr));

        $arr = ['0' => 1, '1' => 2, '2' => 3, '3' => 4];
        $this->assertSame(true, ArrayHelper::isIndexedArray($arr));

        $arr = ['0' => 1, '2' => 2, '3' => 3, '4' => 4];
        $this->assertSame(false, ArrayHelper::isIndexedArray($arr));
    }

    public function testObject2Array()
    {
        $str = 'O:9:"astdClass":1:{s:1:"a";s:1:"a";}';
        $phpIncompleteClass = unserialize($str);
        $obj = new StdClass();
        $obj->a = 'a';
        $obj->b = ['aa' => 'aa', 'bb' => 'bb'];
        $obj->c = new StdClass();
        $obj->c->aa = 'aa';
        $obj->d = $phpIncompleteClass;
        $obj->resource1 = fopen(__FILE__, 'r');
        $obj->resource2 = fopen(__FILE__, 'r');
        fclose($obj->resource2);

        $expectedResult = [
            'a' => 'a',
            'b' => ['aa' => 'aa', 'bb' => 'bb'],
            'c' => ['aa' => 'aa'],
//            'd' => [
//                '__PHP_Incomplete_Class_Name' => 'astdClass',
//                'a' => 'a',
//            ]
        ];
        $this->assertSame($expectedResult, ArrayHelper::object2Array($obj));
        fclose($obj->resource1);
    }

}
