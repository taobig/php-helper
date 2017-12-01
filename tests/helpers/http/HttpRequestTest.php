<?php

use taobig\helpers\http\HttpRequest;

class HttpRequestTest extends TestCase
{

    public function testGet()
    {
        $content = (new HttpRequest())->get("localhost:8080", 10);
        $this->assertSame("Hello world!", $content);
    }

    public function testPostJson()
    {
        $time = time();
        $params = ['time' => $time];
        $content = (new HttpRequest())->postJson("localhost:8080/json", $params, 10);
        $this->assertSame("string", gettype($content));
        $obj = json_decode($content);
        $this->assertSame("object", gettype($obj));
        $this->assertSame($time, $obj->time);
    }

    public function testPostForm()
    {
        $time = time();
        $params = ['time' => $time];
        $content = (new HttpRequest())->postForm("localhost:8080/form", $params, 10);
        $this->assertSame("string", gettype($content));
        $this->assertSame("time={$time}", $content);
    }

}
