<?php

namespace taobig\helpers;

class NumberHelper
{

    /**
     *
     * @param int $n 0 <= $n <= 9
     * @return string
     */
    public static function singleDigitToSimpleChinese(int $n): string
    {
        if ($n < 0 || $n > 9) {
            return '';
        }
        return ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九'][$n];
    }

}