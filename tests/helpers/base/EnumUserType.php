<?php

declare(strict_types=1);

namespace taobig\tests\helpers\helpers\base;

use taobig\helpers\base\EnumBase;

class EnumUserType extends EnumBase
{

    public static function labels(): array
    {
        return [
            1 => 'A',
            2 => 'B',
        ];
    }
}