<?php

namespace taobig\helpers\base;


abstract class EnumBase
{

    abstract public static function labels(): array;

    public static function dropdownList($defaultValue = null, string $name = ''): array
    {
        if ($defaultValue !== null) {
            $result[$defaultValue] = $name;
            foreach (static::labels() as $key => $val) {
                $result[$key] = $val;
            }
            return $result;
        } else {
            return static::labels();
        }
    }

    public static function label($val)
    {
        return static::labels()[$val] ?? $val;
    }

    public static function exists($val): bool
    {
        return isset(static::labels()[$val]);
    }

}