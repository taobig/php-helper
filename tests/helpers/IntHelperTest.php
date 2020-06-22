<?php

use taobig\helpers\IntHelper;

class IntHelperTest extends TestCase
{

    public function testStartsWith()
    {
        $this->assertSame(false, IntHelper::isUnsignedInt("hello world"));
        $this->assertSame(false, IntHelper::isUnsignedInt("111 2222"));
        $this->assertSame(true, IntHelper::isUnsignedInt("111"));
        $this->assertSame(false, IntHelper::isUnsignedInt("111.11"));
        $this->assertSame(true, IntHelper::isUnsignedInt("0"));
        $this->assertSame(false, IntHelper::isUnsignedInt("1.1111111111111E+28"));
    }


}
