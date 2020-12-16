<?php

namespace taobig\tests\helpers\helpers;

use taobig\helpers\UnitHelper;

class UnitHelperTest extends \TestCase
{

    public function testFormatBytes()
    {
        $this->assertSame("1.00B", UnitHelper::formatBytes(1, 2));
        $this->assertSame("1.00KB", UnitHelper::formatBytes(pow(2, 10), 2));
        $this->assertSame("1.00MB", UnitHelper::formatBytes(pow(2, 20), 2));
        $this->assertSame("1.00GB", UnitHelper::formatBytes(pow(2, 30), 2));
        $this->assertSame("1.00TB", UnitHelper::formatBytes(pow(2, 40), 2));
        $this->assertSame("1.00PB", UnitHelper::formatBytes(pow(2, 50), 2));
        $this->assertSame("1.00EB", UnitHelper::formatBytes(pow(2, 60), 2));

        $this->assertSame("2.00B", UnitHelper::formatBytes(2, 2));
        $this->assertSame("1.50KB", UnitHelper::formatBytes(pow(2, 10) + pow(2, 9), 2));
        $this->assertSame("1.50MB", UnitHelper::formatBytes(pow(2, 20) + pow(2, 19), 2));
        $this->assertSame("1.50GB", UnitHelper::formatBytes(pow(2, 30) + pow(2, 29), 2));
        $this->assertSame("1.50TB", UnitHelper::formatBytes(pow(2, 40) + pow(2, 39), 2));
        $this->assertSame("1.50PB", UnitHelper::formatBytes(pow(2, 50) + pow(2, 49), 2));
        $this->assertSame("1.50EB", UnitHelper::formatBytes(pow(2, 60) + pow(2, 59), 2));

        $this->assertSame("1.00KB", UnitHelper::formatBytes(1025, 2));
        $this->assertSame("1.001KB", UnitHelper::formatBytes(1025, 3));
        $this->assertSame("1.0010KB", UnitHelper::formatBytes(1025, 4));

    }


}
