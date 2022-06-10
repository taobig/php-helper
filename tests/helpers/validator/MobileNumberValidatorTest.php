<?php

declare(strict_types=1);

namespace taobig\tests\helpers\helpers\validator;

use taobig\helpers\validator\MobileNumberValidator;

class MobileNumberValidatorTest extends \TestCase
{

    public function testIsValidMobileNumber()
    {
        $this->assertSame(true, MobileNumberValidator::isValidMobileNumber("13800001111"));

        $this->assertSame(false, MobileNumberValidator::isValidMobileNumber("138"));
        $this->assertSame(false, MobileNumberValidator::isValidMobileNumber("138000A1111"));
        $this->assertSame(false, MobileNumberValidator::isValidMobileNumber("1380001000"));
        $this->assertSame(false, MobileNumberValidator::isValidMobileNumber("138000100020"));

        $this->assertSame(true, MobileNumberValidator::isValidMobileNumber("19200010002"));
        $this->assertSame(false, MobileNumberValidator::isValidMobileNumber("12800001111"));
        $this->assertSame(false, MobileNumberValidator::isValidMobileNumber("23800001111"));

    }

}

