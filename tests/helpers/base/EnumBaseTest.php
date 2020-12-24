<?php

declare(strict_types=1);

namespace taobig\tests\helpers\helpers\base;


class EnumBaseTest extends \TestCase
{

    public function testSplit()
    {
        $this->assertSame(2, count(EnumUserType::labels()));
        $this->assertSame(2, count(EnumUserType::dropdownList()));
        $this->assertSame(3, count(EnumUserType::dropdownList('')));

        $this->assertSame('A', EnumUserType::label(1));
        $this->assertSame(11, EnumUserType::label(11));

        $this->assertSame(false, EnumUserType::exists(11));

    }

}
