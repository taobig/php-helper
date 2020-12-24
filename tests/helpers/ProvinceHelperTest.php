<?php

declare(strict_types=1);

namespace taobig\tests\helpers\helpers;

use taobig\helpers\ProvinceHelper;

class ProvinceHelperTest extends \TestCase
{

    public function testProvince()
    {
        $provinces = (new ProvinceHelper())->getProvinces();
        $this->assertSame(31, count($provinces));
    }

}
