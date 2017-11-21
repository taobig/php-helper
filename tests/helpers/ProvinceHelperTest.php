<?php

use taobig\helpers\ProvinceHelper;

class ProvinceHelperTest extends TestCase
{

    public function testStartsWith()
    {
        $provinces = (new ProvinceHelper())->getProvinces();
        $this->assertSame(31, count($provinces));
    }

}
