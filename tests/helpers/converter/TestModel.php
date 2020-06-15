<?php


namespace taobig\tests\helpers\helpers\converter;


use taobig\helpers\converter\BaseConverter;
use taobig\helpers\converter\ConverterException;
use taobig\helpers\converter\TypedArrayList;

class TestModel extends BaseConverter
{
    /** @var string */
    public $name = '';
    /** @var string */
    public $start_time = '';//": "2020-01-01 00:00:00",

    /** @var TestSubModel[] */
    public $list = [];

    /** @var TestSubModel[][] */
    public $multi_list = [];

    protected function getTypedArrayListMapping(): array
    {
        return [
            'list' => new TypedArrayList(TestSubModel::class),
            'multi_list' => new TypedArrayList(TestSubModel::class, 2),
        ];
    }

    public function validate()
    {
        if (empty($this->name)) {
            throw new ConverterException('name cannot be empty');
        }
    }
}