<?php


namespace taobig\tests\helpers\helpers\converter;


use taobig\helpers\converter\BaseConverter;
use taobig\helpers\converter\ConverterException;
use taobig\helpers\converter\TypedArrayList;

class TestTypedModel extends BaseConverter
{
    public int $age = 0;
    public bool $is_ok = false;
    public float $score = 0.0;
    public string $name = '';
    public ?int $number = null;
    public ?string $str = null;

    public string $start_time = '';

    /** @var TestSubModel[] */
    public array $list = [];

    /** @var TestSubModel[][] */
    public array $multi_list = [];

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