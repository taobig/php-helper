<?php

namespace taobig\helpers;

class IntHelper
{

    /**
     * 不支持检测科学计数法格式的正整数
     * @param string $str
     * @return bool
     */
    public static function isUnsignedInt(string $str): bool
    {
        if (preg_match('/^\d+$/', $str)) {
            return true;
        }
        return false;
    }

}