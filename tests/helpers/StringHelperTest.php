<?php

use taobig\helpers\StringHelper;

class StringHelperTest extends TestCase
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

}