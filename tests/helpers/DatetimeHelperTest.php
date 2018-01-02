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

}
