<?php

namespace taobig\tests\helpers\helpers\converter;

use taobig\helpers\converter\ConverterException;
use TestCase;

class BaseConverterTest extends TestCase
{

    public function testLoadWithUndeclaredPropertyNameTypeOnException()
    {
        $requestBody = <<<JSON
{
    "name": "",
    "start_time": "2020-01-01 00:00:00"
}
JSON;
        $requestArr = json_decode($requestBody, true);
        $this->expectException(ConverterException::class);//TestModel property name has been declared without a type.
        $params = TestModel::load($requestArr);
    }

    private const JSON = <<<JSON
{
    "age": 18,
    "is_ok": true,
    "score": 1.1,
    "name": "hello",
    "number": null,
    "start_time": "2020-01-01 00:00:00",
    "str": null,
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

    public function testLoadTypedClass()
    {
        $requestBody = self::JSON;
        $requestArr = json_decode($requestBody, true);
        $params = TestTypedModel::load($requestArr);
        $this->assertNotSame('18', $params->age);
        $this->assertSame(18, $params->age);
        $this->assertNotSame('true', $params->is_ok);
        $this->assertSame(true, $params->is_ok);
        $this->assertSame(1.1, $params->score);
        $this->assertSame('2020-01-01 00:00:00', $params->start_time);
        $this->assertEquals('foo', $params->list[0]->name);
        $this->assertEquals('bar', $params->multi_list[0][0]->name);
        $this->assertSame(null, $params->number);
        $this->assertSame(null, $params->str);

    }

    public function testLoadTypedClassWithoutValidate()
    {
        $requestBody = <<<JSON
{
    "age": 18,
    "is_ok": true,
    "score": 1.1,
    "name": null,
    "number": null,
    "start_time": "2020-01-01 00:00:00",
    "str": null,
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
        $params = TestTypedModel::load($requestArr, false);
        $this->assertSame(null, $params->number);
    }

    public function testLoadTypedClassOnException()
    {
        $requestBody = <<<JSON
{
    "age": 18,
    "is_ok": true,
    "score": 1.1,
    "name": null,
    "number": null,
    "start_time": "2020-01-01 00:00:00",
    "str": null,
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
        $this->expectException(ConverterException::class);//TestTypedModel::validate() name cannot be empty
        $params = TestTypedModel::load($requestArr);
        $this->assertSame(null, $params->number);
    }

}