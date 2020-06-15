<?php


namespace taobig\tests\helpers\helpers\converter;

use taobig\helpers\converter\BaseConverter;
use taobig\helpers\converter\ConverterException;

class TestSubModel extends BaseConverter
{
    /** @var string */
    public $name = '';

    public function validate()
    {
        if (empty($this->name)) {
            throw new ConverterException('name cannot be empty');
        }
    }
}