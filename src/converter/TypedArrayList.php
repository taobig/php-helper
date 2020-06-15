<?php


namespace taobig\helpers\converter;


class TypedArrayList {

    private $type;
    /** @var int 维度，1-表示是个一维的{$type}类型数据 */
    private $dimensional = 1;

    public function __construct(string $type, int $dimensional = 1) {
        $this->type = $type;
        $this->dimensional = $dimensional;
    }

    public function getTargetType(): string {
        return $this->type;
    }

    public function getDimensional(): int {
        return $this->dimensional;
    }

}