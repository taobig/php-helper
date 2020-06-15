<?php

namespace taobig\tests\helpers\helpers\converter;

use taobig\helpers\converter\ConverterException;
use TestCase;

class BaseConverterTest extends TestCase
{

    public function testLoad()
    {
        $requestBody = <<<JSON
{
    "name": "hello",
    "start_time": "2020-01-01 00:00:00",
    "list": [
        {
            "name": "foo"
        }
    ],
    "multi_list": [
        [
            {
                "name": "bar"
            }
        ]
    ]
}
JSON;
        $requestArr = json_decode($requestBody, true);
        $params = TestModel::load($requestArr);
        $this->assertSame('hello', $params->name);
        $this->assertSame('2020-01-01 00:00:00', $params->start_time);
        $this->assertEquals('foo', $params->list[0]->name);
        $this->assertEquals('bar', $params->multi_list[0][0]->name);

        $this->expectException(ConverterException::class);
        $requestBody = <<<JSON
{
    "name": "",
    "start_time": "2020-01-01 00:00:00"
}
JSON;
        $requestArr = json_decode($requestBody, true);
        TestModel::load($requestArr);
    }

    public function testLoadWithException()
    {
        $requestBody = <<<JSON
{
    "name": "hello",
    "start_time": "2020-01-01 00:00:00",
    "list": [
        {
            "name": "foo"
        }
    ],
    "list2":[]
}
JSON;
        $requestArr = json_decode($requestBody, true);
        $this->expectException(ConverterException::class);
        $params = TestModel2::load($requestArr);
        $this->assertSame('hello', $params->name);
        $this->assertSame('2020-01-01 00:00:00', $params->start_time);
        $this->assertEquals('foo', $params->list[0]->name);

    }

    public function testLoadWithoutValidate()
    {
        $requestBody = <<<JSON
{
    "name": "",
    "start_time": "2020-01-01 00:00:00"
}
JSON;
        $requestArr = json_decode($requestBody, true);
        $params = TestModel::load($requestArr, false);
        $this->assertSame('', $params->name);
        $this->assertSame('2020-01-01 00:00:00', $params->start_time);

    }
}