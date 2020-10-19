<?php


namespace taobig\tests\helpers\helpers\converter;


use taobig\helpers\converter\BaseConverter;
use taobig\helpers\converter\ConverterException;
use taobig\helpers\converter\TypedArrayList;

class TestModel2 extends BaseConverter
{
    /** @var string */
    public $name = '';
    /** @var string */
    public $start_time = '';//": "2020-01-01 00:00:00",

    #[TypedArrayList(TestSubModel::class)]
    /** @var TestSubModel[] */
    public $list = [];

    #[TypedArrayList(int::class)]
    /** @var int[] */
    public $list2 = [];

    public function validate()
    {
        if (empty($this->name)) {
            throw new ConverterException('name cannot be empty');
        }
    }
}