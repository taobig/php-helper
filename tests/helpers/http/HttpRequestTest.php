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
        $key = 'Customer-Header';
        $value = 'hello';
        $headers = [$key => $value];
        $content = (new HttpRequest())->postJson("localhost:8080/json", $params, 10, $headers);
        $this->assertSame("string", gettype($content));
        $obj = json_decode($content);
        $this->assertSame("object", gettype($obj));
        $this->assertSame($time, $obj->time);

        $this->assertSame($value, $obj->headers->{$key});
    }

    public function testPostForm()
    {
        $time = time();
        $params = ['time' => $time];
        $content = (new HttpRequest())->postForm("localhost:8080/form", $params, 10);
        $this->assertSame("string", gettype($content));
        $this->assertSame("time={$time}", $content);
    }

    public function testPostFile()
    {
        $time = time();
        $params = [
            [
                'name' => 'file1',
                'contents' => file_get_contents(__FILE__),
                'filename' => basename(__FILE__),
            ],
            [
                'name' => 'file2',
                'contents' => file_get_contents(__FILE__),
                'filename' => basename(__FILE__),
            ],
            [
                'name' => 'file_size',
                'contents' => filesize(__FILE__),
            ],
            [
                'name' => 'time',
                'contents' => $time,
            ],
        ];
        $content = (new HttpRequest())->postFile("localhost:8080/file", $params, 10);
        var_dump($content);
        $this->assertSame("string", gettype($content));
        $json = json_decode($content, true);
        $this->assertSame($time, intval($json['post']['time']));
        $this->assertSame($json['files']['file1']['size'], intval($json['post']['file_size']));
    }

}
