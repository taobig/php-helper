<?php

namespace taobig\helpers;

use DateTime;
use DateTimeZone;

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

    public static function getDate(): string
    {
        return date('Y-m-d');
    }

    /**
     * @param int|string|DateTime|null $referenceTime time() or "2019-07-31" or DateTime
     * @return false|string
     */
    public static function getYesterdayDate($referenceTime = null)
    {
        $time = time();
        if (is_int($referenceTime)) {
            $time = $referenceTime;
        } else if (is_string($referenceTime)) {
            $time = strtotime($referenceTime);
        } else if ($referenceTime instanceof DateTime) {
            $time = $referenceTime->getTimestamp();
        }
        return date("Y-m-d", strtotime("-1 days", $time));
    }

    /**
     * @param int|string|DateTime|null $referenceTime time() or "2019-07-31" or DateTime
     * @return false|string
     */
    public static function getTomorrowDate($referenceTime = null)
    {
        $time = time();
        if (is_int($referenceTime)) {
            $time = $referenceTime;
        } else if (is_string($referenceTime)) {
            $time = strtotime($referenceTime);
        } else if ($referenceTime instanceof DateTime) {
            $time = $referenceTime->getTimestamp();
        }
        return date("Y-m-d", strtotime("+1 days", $time));
    }

    /**
     * this method need 64-bit PHP runtime.(32-bit时只能表示67年的时间差)
     * @param string $strDtLeft
     * @param string $strDtRight
     * @return int
     * @throws \Exception
     */
    public static function datetimeDiff(string $strDtLeft, string $strDtRight): int
    {
        $dtLeft = new DateTime($strDtLeft);
        $dtRight = new DateTime($strDtRight);

        return $dtLeft->getTimestamp() - $dtRight->getTimestamp();
    }

    /**
     * @param int $timestamp
     * @return DateTime
     * @throws \Exception
     */
    public static function convertTimestampToDatetime(int $timestamp): DateTime
    {
        return (new DateTime())->setTimestamp($timestamp);
    }

    /**
     * @param string $datetime
     * @param DateTimeZone|null $timezone
     * @return DateTime
     * @throws \Exception
     */
    public static function convertStringToDatetime(string $datetime, ?DateTimeZone $timezone = null): DateTime
    {
        return new DateTime($datetime, $timezone);
    }

    /**
     * @param string $dt '2020-03-18 00:00:00'
     * @param string $targetTimezone eg:'UTC','Asia/Shanghai'...
     * @param string|null $sourceTimezone eg:'Asia/Shanghai'..., If $timezone is omitted, the current timezone will be used
     * @return DateTime
     * @throws \Exception
     */
    public static function convertTimezone(string $dt, string $targetTimezone, string $sourceTimezone = null): DateTime
    {
        $targetDatetime = new DateTime($dt, $sourceTimezone ? new DateTimeZone($sourceTimezone) : null);
        $targetDatetime->setTimezone(new DateTimeZone($targetTimezone));
        return $targetDatetime;
    }

    /**
     * @param string $time String representing the time.
     * @param string $format Format accepted by date().
     * @return bool
     */
    public static function validate(string $time, string $format = 'Y-m-d'): bool
    {
        $dt = DateTime::createFromFormat($format, $time);
        return $dt && $dt->format($format) === $time;
    }

}