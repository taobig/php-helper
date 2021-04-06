<?php

namespace taobig\helpers;

class IntHelper
{

    /**
     * 1. 保持使用当前运行时默认的浮点数精度：https://www.php.net/manual/en/language.types.float.php
     * 2. 不支持大于PHP_INT_MAX（有符号的最大整数值）的无符号整数值
     * @param string|int|float $val
     * @return bool
     */
    public static function isUnsignedInt($val): bool
    {
        if (is_int($val)) {
            return $val > 0;
        } else if (is_float($val)) {
            if (ctype_digit(strval($val))) {
                return $val > 0;
            }
        } else if (is_string($val)) {
            if (preg_match('/^\d+$/', $val)) {
                return true;
            }
        }
        return false;
    }

}