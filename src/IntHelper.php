<?php

namespace taobig\helpers;

class IntHelper
{

    /**
     * 不支持检测科学计数法格式的正整数
     * @param string|int $val
     * @return bool
     */
    public static function isUnsignedInt($val): bool
    {
        if (is_int($val)) {
            return $val > 0;
        } else if (is_string($val)) {
            if (preg_match('/^\d+$/', $val)) {
                return true;
            }
        }
        return false;
    }

}