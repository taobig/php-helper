<?php

namespace taobig\tests\helpers\helpers;

use taobig\helpers\IntHelper;
use TestCase;

class IntHelperTest extends TestCase
{

    public function testIsUnsignedInt()
    {
        $this->assertSame(true, IntHelper::isUnsignedInt(111));
        $this->assertSame(true, IntHelper::isUnsignedInt(111_222_3));//as of PHP 7.4.0

        $this->assertSame(false, IntHelper::isUnsignedInt("hello world"));
        $this->assertSame(false, IntHelper::isUnsignedInt("111 2222"));
        $this->assertSame(true, IntHelper::isUnsignedInt("111"));
        $this->assertSame(false, IntHelper::isUnsignedInt("111 "));
        $this->assertSame(false, IntHelper::isUnsignedInt(" 111"));
        $this->assertSame(false, IntHelper::isUnsignedInt("111.11"));
        $this->assertSame(true, IntHelper::isUnsignedInt("0"));
        $this->assertSame(false, IntHelper::isUnsignedInt("1.1111111111111E+28"));
        $this->assertSame(true, IntHelper::isUnsignedInt(1E+8));
        $this->assertSame(true, IntHelper::isUnsignedInt(1E+10));
        $this->assertSame(false, IntHelper::isUnsignedInt(1E+18));
        $this->assertSame(true, IntHelper::isUnsignedInt(1.00));
        $this->assertSame(true, IntHelper::isUnsignedInt(floatval(2)));
        $this->assertSame(false, IntHelper::isUnsignedInt(1.111));//float

        $this->assertSame(false, IntHelper::isUnsignedInt(PHP_INT_MAX + 1));
    }


}
