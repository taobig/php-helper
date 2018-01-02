<?php

namespace taobig\helpers;

class DatetimeHelper
{

    /**
     * this method need 64-bit PHP runtime.
     * @return int
     */
    public static function millisecondTimestamp(): int
    {
        $mt = explode(' ', microtime());
        return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
    }
}