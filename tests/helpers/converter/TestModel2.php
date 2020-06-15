<?php


namespace taobig\tests\helpers\helpers\converter;


use taobig\helpers\ArrayHelper;
use taobig\helpers\converter\BaseConverter;
use taobig\helpers\converter\ConverterException;
use taobig\helpers\converter\TypedArrayList;
use taobig\helpers\UnitHelper;

class TestModel2 extends BaseConverter
{
    /** @var string */
    public $name = '';
    /** @var string */
    public $start_time = '';//": "2020-01-01 00:00:00",

    /** @var TestSubModel[] */
    public $list = [];

    /** @var int[] */
    public $list2 = [];

    protected function getTypedArrayListMapping(): array
    {
        return [
            'list' => new TypedArrayList(TestSubModel::class),
            'list2' => new TypedArrayList(ArrayHelper::class),
        ];
    }

    public function validate()
    {
        if (empty($this->name)) {
            throw new ConverterException('name cannot be empty');
        }
    }
}