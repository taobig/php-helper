<?php

use taobig\helpers\base\StaticBase;

class SingleInstanceBaseTest extends TestCase {

    public function testError() {
        $this->expectException(\Error::class);
        $staticTest = new \SingleInstanceTest();
        $staticTest->hello();
    }

    public function testInstance() {
        $this->assertSame("Hello World!", SingleInstanceTest::getInstance()->hello());
    }

}


class SingleInstanceTest extends StaticBase
{
    public function hello(){
        return "Hello World!";
    }
}
