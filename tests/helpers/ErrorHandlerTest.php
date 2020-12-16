<?php

namespace taobig\tests\helpers\helpers;

use taobig\helpers\ErrorHandler;

class ErrorHandlerTest extends \TestCase
{

    public function testRandom()
    {
        $this->expectException(\ErrorException::class);
        (new ErrorHandler())->init();
        $arr = [];
        var_dump($arr[1] + 5);
    }

}
