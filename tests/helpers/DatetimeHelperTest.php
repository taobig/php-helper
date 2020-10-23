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

    public function testGetDatetime()
    {
        for ($i = 1; $i < 200; ++$i) {
            $dt = DatetimeHelper::getDatetime();
            usleep(500000);
            $this->assertMatchesRegularExpression("/^[2-9][0-9]{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) [0-5]\d:[0-5]\d:[0-5]\d$/", $dt);
        }
    }

    public function testGetDate()
    {
        $dt = DatetimeHelper::getDate();
        $this->assertMatchesRegularExpression("/^[2-9][0-9]{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $dt);
    }

    public function testGetYesterdayDate()
    {
        $dt = DatetimeHelper::getYesterdayDate();
        $this->assertSame(date('Y-m-d', time() - 24 * 3600), $dt);

        $dt = DatetimeHelper::getYesterdayDate('2019-07-31');
        $this->assertSame('2019-07-30', $dt);

        $dt = DatetimeHelper::getYesterdayDate(strtotime('2019-07-31'));
        $this->assertSame('2019-07-30', $dt);

        $date = new DateTime();
        $date->setTimestamp(strtotime('2019-07-31'));
        $dt = DatetimeHelper::getYesterdayDate($date);
        $this->assertSame('2019-07-30', $dt);
    }

    public function testGetTomorrowDate()
    {
        $dt = DatetimeHelper::getTomorrowDate();
        $this->assertSame(date('Y-m-d', time() + 24 * 3600), $dt);

        $dt = DatetimeHelper::getTomorrowDate('2019-07-31');
        $this->assertSame('2019-08-01', $dt);

        $dt = DatetimeHelper::getTomorrowDate(strtotime('2019-07-31'));
        $this->assertSame('2019-08-01', $dt);

        $date = new DateTime();
        $date->setTimestamp(strtotime('2019-07-31'));
        $dt = DatetimeHelper::getTomorrowDate($date);
        $this->assertSame('2019-08-01', $dt);
    }

    public function testConvertTimestampToDatetime()
    {
        $time = time();
        $dt = DatetimeHelper::convertTimestampToDatetime($time);
        $this->assertSame($time, $dt->getTimestamp());
    }

    public function testConvertStringToDatetime()
    {
        $str = '2019-08-01 00:00:00';
        $dt = DatetimeHelper::convertStringToDatetime($str);
        $this->assertSame($str, $dt->format('Y-m-d H:i:s'));

        $this->expectException(\Exception::class);
        $str = '2019-08---01 00:00:00';
        $dt = DatetimeHelper::convertStringToDatetime($str);
        $this->assertSame($str, $dt->format('Y-m-d H:i:s'));
    }

    public function testConvertTimezone()
    {
        $str = '2019-08-01 00:00:00';
        $dt = DatetimeHelper::convertTimezone($str, 'Asia/Shanghai', 'UTC');
        $this->assertSame('2019-08-01 08:00:00', $dt->format('Y-m-d H:i:s'));

        date_default_timezone_set('Asia/Shanghai');
        $dt = DatetimeHelper::convertTimezone($str, 'UTC');
        $this->assertSame('2019-07-31 16:00:00', $dt->format('Y-m-d H:i:s'));
    }

    public function testValidate()
    {
        $this->assertSame(false, DatetimeHelper::validate('2020-13-01'));
        $this->assertSame(false, DatetimeHelper::validate('20202-01-01'));
        $this->assertSame(false, DatetimeHelper::validate('2020-01-32'));
        $this->assertSame(false, DatetimeHelper::validate('2012-2-25'));
        $this->assertSame(true, DatetimeHelper::validate('2020-12-01'));
        $this->assertSame(true, DatetimeHelper::validate('2020', 'Y'));
        $this->assertSame(false, DatetimeHelper::validate('12020', 'Y'));
    }

}