<?php

use taobig\helpers\NumberHelper;


class NumberHelperTest extends TestCase
{

    public function testSingleDigitToSimpleChinese()
    {
        $this->assertSame('零', NumberHelper::singleDigitToSimpleChinese(0));
        $this->assertSame('一', NumberHelper::singleDigitToSimpleChinese(1));
        $this->assertSame('二', NumberHelper::singleDigitToSimpleChinese(2));
        $this->assertSame('三', NumberHelper::singleDigitToSimpleChinese(3));
        $this->assertSame('四', NumberHelper::singleDigitToSimpleChinese(4));
        $this->assertSame('五', NumberHelper::singleDigitToSimpleChinese(5));
        $this->assertSame('六', NumberHelper::singleDigitToSimpleChinese(6));
        $this->assertSame('七', NumberHelper::singleDigitToSimpleChinese(7));
        $this->assertSame('八', NumberHelper::singleDigitToSimpleChinese(8));
        $this->assertSame('九', NumberHelper::singleDigitToSimpleChinese(9));
    }

}
