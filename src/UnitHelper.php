<?php

namespace taobig\helpers;


class UnitHelper
{

    //如果计算的结果正好是整数，那么$precision设置无效
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        //1EB == 2^60 Bytes
        //1ZB == 2^70 Bytes
        //1YB == 2^80 Bytes
        //1DB == 2^90 Bytes
        //1NB == 2^100 Bytes
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'];
        $base = log($bytes, 1024);
        return number_format(round(pow(1024, $base - floor($base)), $precision), $precision) . $suffixes[floor($base)];
    }

}