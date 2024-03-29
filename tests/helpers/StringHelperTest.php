<?php

declare(strict_types=1);

namespace taobig\tests\helpers\helpers;

use taobig\helpers\StringHelper;

class StringHelperTest extends \TestCase
{

    public function testStartsWith()
    {
        $this->assertSame(true, StringHelper::startsWith("hello world", "h"));
        $this->assertSame(true, StringHelper::startsWith("hello world", "hello "));
        $this->assertSame(true, StringHelper::startsWith("hello world", ""));

        $this->assertSame(false, StringHelper::startsWith("hello world", "H"));
        $this->assertSame(false, StringHelper::startsWith("hello world", "Hello"));

        $this->assertSame(false, StringHelper::startsWith("hello", "hello world"));
        $this->assertSame(false, StringHelper::startsWith("", "hello world"));

        $this->assertSame(true, StringHelper::startsWith("https://google.com", "https://"));
    }

    public function testEndsWith()
    {
        $this->assertSame(true, StringHelper::endsWith("hello world", "world"));
        $this->assertSame(true, StringHelper::endsWith("hello world", " world"));
        $this->assertSame(true, StringHelper::endsWith("hello world", "hello world"));
        $this->assertSame(true, StringHelper::endsWith("hello world", ""));
        $this->assertSame(false, StringHelper::endsWith("hello world", "World"));

        $this->assertSame(false, StringHelper::endsWith("world", "hello world"));
        $this->assertSame(false, StringHelper::endsWith("", "hello world"));
    }

    public function testContains()
    {
        $this->assertSame(true, StringHelper::contains("hello world", "world"));
        $this->assertSame(true, StringHelper::contains("hello world", " world"));
        $this->assertSame(true, StringHelper::contains("hello world", ""));

        $this->assertSame(false, StringHelper::contains("", "hello world"));
        $this->assertSame(false, StringHelper::contains("hello world", "World"));
    }


    public function testStripLeft()
    {
        $this->assertSame("ello world", StringHelper::stripLeft("hello world", "h"));
        $this->assertSame("world", StringHelper::stripLeft("hello world", "hello "));

        $this->assertSame("hello world", StringHelper::stripLeft("hello world", ""));
        $this->assertSame("hello world", StringHelper::stripLeft("hello world", "H"));
        $this->assertSame("hello world", StringHelper::stripLeft("hello world", "hellO"));
        $this->assertSame("hello world", StringHelper::stripLeft("hello world", "hello  "));
        $this->assertSame("hello world", StringHelper::stripLeft("hello world", "llo "));
        $this->assertSame("hello", StringHelper::stripLeft("hello", "hello world"));
        $this->assertSame("", StringHelper::stripLeft("", "hello world"));

        $this->assertSame("google.com", StringHelper::stripLeft("https://google.com", "https://"));
    }


    public function testStripRight()
    {
        $this->assertSame("hello ", StringHelper::stripRight("hello world", "world"));
        $this->assertSame("hello", StringHelper::stripRight("hello world", " world"));
        $this->assertSame("", StringHelper::stripRight("hello world", "hello world"));

        $this->assertSame("hello world", StringHelper::stripRight("hello world", ""));
        $this->assertSame("hello world", StringHelper::stripRight("hello world", "World"));
        $this->assertSame("hello world", StringHelper::stripRight("hello world", "hello"));
        $this->assertSame("world", StringHelper::stripRight("world", "hello world"));
        $this->assertSame("", StringHelper::stripRight("", "hello world"));
    }

    public function testMbRtrim()
    {
        $this->assertSame("hello ", StringHelper::mb_rtrim("hello world", "world"));
        $this->assertSame("he", StringHelper::mb_rtrim("hello world", " world"));
        $this->assertSame("你好，", StringHelper::mb_rtrim("你好，世界", "世界"));

//        $this->assertSame("互联网产�", trim("互联网产品、", "、"));
        $this->assertSame("互联网产品", StringHelper::mb_rtrim("互联网产品、", "、"));
        $this->assertSame("", StringHelper::mb_rtrim("、", "、"));
        $this->assertSame("", StringHelper::mb_rtrim("", "、"));

        $this->assertSame("汉语言", StringHelper::mb_rtrim("汉语言、", "、"));
    }

    public function testLeftPadding()
    {
        $this->assertSame("09", StringHelper::leftPadding("9", 2, '0'));
        $this->assertSame("10", StringHelper::leftPadding("10", 2, '0'));
        $this->assertSame("009", StringHelper::leftPadding("9", 3, '0'));
        $this->assertSame("aa9", StringHelper::leftPadding("9", 3, 'a'));
        $this->assertSame("990", StringHelper::leftPadding("990", 3, '0'));

        $this->assertSame("9", StringHelper::leftPadding("9", 3, '00'));
    }

    public function testPrepend()
    {
        $this->assertSame("09", StringHelper::prepend("9", 2, '0'));
        $this->assertSame("10", StringHelper::prepend("10", 2, '0'));
        $this->assertSame("009", StringHelper::prepend("9", 3, '0'));
        $this->assertSame("aa9", StringHelper::prepend("9", 3, 'a'));
        $this->assertSame("990", StringHelper::prepend("990", 3, '0'));

        $this->assertSame("9", StringHelper::prepend("9", 3, '00'));
    }

    public function testCombine()
    {
        $this->assertSame("", StringHelper::combineSpaces(""));
        $this->assertSame("aaa bbb ccc", StringHelper::combineSpaces("aaa  bbb  ccc"));
        $this->assertSame("aaa bbb ccc", StringHelper::combineSpaces("aaa bbb    ccc"));
        //全角空格
        $this->assertSame("aaa　bbb ccc", StringHelper::combineSpaces("aaa　bbb    ccc"));
    }

    public function testCombineSpacesInUtf8String()
    {
        $this->assertSame("", StringHelper::combineSpacesInUtf8String(""));
        $this->assertSame("aaa bbb ccc", StringHelper::combineSpacesInUtf8String("aaa  bbb  ccc"));
        $this->assertSame("aaa bbb ccc", StringHelper::combineSpacesInUtf8String("aaa bbb    ccc"));
        //全角空格
        $this->assertSame("aaa　bbb ccc", StringHelper::combineSpacesInUtf8String("aaa　bbb    ccc"));

        $gbkStr = file_get_contents(__DIR__ . '/StringEncodingHelperFile_GBK.source');
        $this->assertSame(null, StringHelper::combineSpacesInUtf8String($gbkStr));
    }

    public function testSnakeCase2Camelcase()
    {
        $this->assertSame("aaaBbbCcc", StringHelper::snakeCase2CamelCase("aaa_bbb_ccc"));
        $this->assertSame("aaaBbbCcc", StringHelper::snakeCase2CamelCase("aaaBbb_ccc"));
        $this->assertSame("aaa2Ccc", StringHelper::snakeCase2CamelCase("aaa_2_ccc"));
    }

    public function testCamelCase2DashCase()
    {
        $this->assertSame("aaa-bbb-ccc", StringHelper::camelCase2KebabCase("aaaBbbCcc"));
        $this->assertSame("aaa-bbb-ccc", StringHelper::camelCase2KebabCase("AaaBbbCcc"));
    }

    public function testCamelCase2SnakeCase()
    {
        $this->assertSame("aaa_bbb_ccc", StringHelper::camelCase2SnakeCase("aaaBbbCcc"));
        $this->assertSame("aaa_bbb_ccc", StringHelper::camelCase2SnakeCase("AaaBbbCcc"));
    }

    public function testSplit()
    {
        $this->assertEquals(['aaa', 'bbb', 'ccc'], StringHelper::split("aaa_bbb_ccc", '_'));
        $this->assertEquals(['aaa', 'bbb', 'ccc'], StringHelper::split("aaa,bbb,ccc", ','));
        $this->assertEquals(['aaa', 'bbb', '', 'ccc'], StringHelper::split("aaa,bbb,,ccc", ','));
        $this->assertEquals(['aaa', 'bbb', 'ccc'], StringHelper::split("aaa,bbb,,ccc", ',', false));
        $this->assertEquals(['aaa', 'bbb,ccc'], StringHelper::split("aaa,,bbb,ccc", ',,'));
        $this->assertEquals(['aaa_bbb_ccc'], StringHelper::split("aaa_bbb_ccc", '__'));
        $this->assertEquals(['aaa_bbb_ccc'], StringHelper::split("aaa_bbb_ccc", '__', false));

        $this->assertNotSame([1, 2, 3], StringHelper::split("1,2,3", ','));
        $this->assertEquals(['1', '2', '3'], StringHelper::split("1,2,3", ','));
        $this->assertSame(['1', '2', '3'], StringHelper::split("1,2,,3", ',', false));

        $this->assertSame([], StringHelper::split("", ',', false));
    }

    public function testSplitException()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame([1, 2, 3], StringHelper::split("aaa_bbb_ccc", ''));
    }

    public function testEquals()
    {
        $this->assertTrue(StringHelper::equals("aaa", "aaa"));
        $this->assertFalse(StringHelper::equals("aaa", "AAa",));
        $this->assertFalse(StringHelper::equals("aaa", "AAAa"));
        $this->assertFalse(StringHelper::equals("aaa", "aaA"));
    }

    public function testEqualsIgnoreCase()
    {
        $this->assertTrue(StringHelper::equalsIgnoreCase("aaa", "aaa"));
        $this->assertTrue(StringHelper::equalsIgnoreCase("aaa", "AAa",));
        $this->assertFalse(StringHelper::equalsIgnoreCase("aaa", "AAAa"));
        $this->assertTrue(StringHelper::equalsIgnoreCase("aaa", "aaA"));
        $this->assertFalse(StringHelper::equalsIgnoreCase("aaa", "aab"));
    }

}
