<?php

use taobig\helpers\MathHelper;

class MathHelperTest extends TestCase
{

    public function testAdd()
    {
        $this->assertSame("0.00", MathHelper::add("hello world", "h"));
        $this->assertSame("3.00", MathHelper::add("1", "2"));
        $this->assertSame("3.13", MathHelper::add("1.01", "2.12"));

        $this->assertSame("2.00", MathHelper::add(" 1", "2"));
        $this->assertSame("2.00", MathHelper::add("hello", "2"));
        $this->assertSame("3.13", MathHelper::add("1.01", "2.12999"));
        $this->assertSame("3.14", MathHelper::add("1.01666", "2.12999"));
        $this->assertSame("2.00", MathHelper::add("12hello", "2"));
    }

    public function testSub()
    {
        $this->assertSame("3.00", MathHelper::sub("5", "2"));
        $this->assertSame("4.98", MathHelper::sub("5.00", "0.02"));
        $this->assertSame("2.01", MathHelper::sub("5.11", "3.10"));
        $this->assertSame("98.52", MathHelper::sub("100.53", "2.01"));
    }

    public function testMul()
    {
        $this->assertSame("6.0000", MathHelper::mul("2", "3", 4));
        $this->assertNotSame("6", MathHelper::mul("2", "3"));

        $this->assertSame("7.26000", MathHelper::mul("2.2", "3.3", 5));

        $this->assertSame("6.00", MathHelper::mul("2", "3"));
        $this->assertSame("3.33", MathHelper::mul("1.1111111", "3"));
    }

    public function testDiv()
    {
        $this->assertSame("0.01", MathHelper::div("1", "100"));
        $this->assertSame("2.00", MathHelper::div("4.04", "2.02"));
        $this->assertSame("3333.33", MathHelper::div("1", "0.0003"));
    }

    public function testComp()
    {
        $this->assertSame(0, MathHelper::comp("3", "3.00"));
        $this->assertSame(0, MathHelper::comp("3", "3.001"));
        $this->assertSame(0, MathHelper::comp("3", "3.009"));

        $this->assertSame(0, MathHelper::comp(" 3", "0"));
        $this->assertSame(-1, MathHelper::comp(" 3", "3"));
        $this->assertSame(-1, MathHelper::comp("hello", "3"));
        $this->assertSame(0, MathHelper::comp("12hello", "0"));

    }

}
