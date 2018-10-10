<?php

use taobig\helpers\DatetimeHelper;

class DatetimeHelperTest extends TestCase
{

    public function testMillisecondTimestamp()
    {
        $time = time();
        $millisecond = DatetimeHelper::millisecondTimestamp();
        $second = floor($millisecond / 1000);
        $this->assertSame(true, $time - 1 == $second || $second == $time);
    }

    public function testDatetimeDiff()
    {
        $this->assertSame(0, DatetimeHelper::datetimeDiff("2018-10-10 00:00:00", "2018-10-10 00:00:00"));
        $this->assertSame(1, DatetimeHelper::datetimeDiff("2018-10-10 00:00:01", "2018-10-10 00:00:00"));
        $this->assertSame(-1, DatetimeHelper::datetimeDiff("2018-10-10 00:00:00", "2018-10-10 00:00:01"));

        $this->assertSame(-1, DatetimeHelper::datetimeDiff("20181010000000", "20181010000001"));

        $this->assertSame(86400, DatetimeHelper::datetimeDiff("20181011", "20181010"));
        $this->assertSame(0, DatetimeHelper::datetimeDiff("20181010", "20181010"));

        $this->assertSame(315572198400, DatetimeHelper::datetimeDiff("99991231", "00000000"));
    }

}
