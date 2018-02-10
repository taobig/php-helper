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

}
