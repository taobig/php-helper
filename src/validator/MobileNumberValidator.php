<?php

namespace taobig\helpers\validator;

class MobileNumberValidator
{

    /**
     * Without validating Country Calling Code (references:https://en.wikipedia.org/wiki/List_of_country_calling_codes)
     * rules: https://support.huaweicloud.com/intl/zh-cn/productdesc-msgsms/phone_numbers.html
     * @param string $val
     * @return bool
     */
    public static function isValidMobileNumber(string $val): bool
    {
        return preg_match('/^1([3-9])\d{9}$/', $val) === 1;//but preg_match return 1/0/false
    }

}
