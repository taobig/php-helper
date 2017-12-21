<?php

use taobig\helpers\ErrorHandler;

class ErrorHandlerTest extends TestCase
{


    public function testRandom()
    {
        //TODO: add tests
        $this->expectException(\ErrorException::class);
        (new ErrorHandler())->init();
        $arr = [];
        $sum = $arr[1] + 5;
    }

}
