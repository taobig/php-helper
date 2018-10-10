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

    public static function getDatetime(): string
    {
        return date("Y-m-d H:i:s");
    }

    /**
     * this method need 64-bit PHP runtime.(32-bit时只能表示67年的时间差)
     * @param string $strDtLeft
     * @param string $strDtRight
     * @return int
     */
    public static function datetimeDiff(string $strDtLeft, string $strDtRight): int
    {
        $dtLeft = new \DateTime($strDtLeft);
        $dtRight = new \DateTime($strDtRight);

        return $dtLeft->getTimestamp() - $dtRight->getTimestamp();
    }

}