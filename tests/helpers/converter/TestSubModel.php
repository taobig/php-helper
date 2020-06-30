<?php


namespace taobig\tests\helpers\helpers\converter;

use taobig\helpers\converter\BaseConverter;
use taobig\helpers\converter\ConverterException;

class TestSubModel extends BaseConverter
{
    public string $name = '';

    public function validate()
    {
        if (empty($this->name)) {
            throw new ConverterException('name cannot be empty');
        }
    }
}