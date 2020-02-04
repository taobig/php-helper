<?php

use taobig\helpers\base\SingleInstanceBase;

class SingleInstanceBaseTest extends TestCase {

    public function testError() {
        $this->expectException(\Error::class);
        $staticTest = new \SingleInstanceTest();
        $staticTest->hello();
    }

    public function testInstance() {
        $this->assertSame("Hello World!", SingleInstanceTest::getInstance()->hello());
    }

    public function testClone() {
        $instance = SingleInstanceTest::getInstance();
        $this->expectException(\Error::class);
        clone $instance;
    }


}


class SingleInstanceTest extends SingleInstanceBase
{
    public function hello(){
        return "Hello World!";
    }
}
