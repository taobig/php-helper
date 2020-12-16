<?php

namespace taobig\tests\helpers\helpers;

use taobig\helpers\MathHelper;

class MathHelperTest extends \TestCase
{

    //一个方法里只能有一个expectException
    public function testAdd()
    {
        $this->assertSame("3.00", MathHelper::add("1", "2"));
        $this->assertSame("3.13", MathHelper::add("1.01", "2.12"));

        $this->expectException(\ValueError::class);
        $this->assertSame("2.00", MathHelper::add(" 1", "2"));
    }

    //一个方法里只能有一个expectException
    public function testAdd2()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame("0.00", MathHelper::add("hello world", "h"));
    }

    public function testAdd3()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame("2.00", MathHelper::add("hello", "2"));
        $this->assertSame("3.13", MathHelper::add("1.01", "2.12999"));
        $this->assertSame("3.14", MathHelper::add("1.01666", "2.12999"));
    }

    public function testAdd4()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame("2.00", MathHelper::add("12hello", "2"));
    }

    public function testSub()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame("3.00", MathHelper::sub(" 5", "2"));
        $this->assertSame("3.00", MathHelper::sub("5", "2"));
        $this->assertSame("4.98", MathHelper::sub("5.00", "0.02"));
        $this->assertSame("2.01", MathHelper::sub("5.11", "3.10"));
        $this->assertSame("98.52", MathHelper::sub("100.53", "2.01"));
    }

    public function testMul()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame("6.0000", MathHelper::mul(" 2", "3", 4));
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

    public function testDivException()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame("0.01", MathHelper::div(" 1", "100"));
    }

    public function testDivDivisionByZeroError()
    {
        $this->expectException(\DivisionByZeroError::class);
        $this->assertSame("0.01", MathHelper::div("1", "0"));
    }

    public function testComp()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame(0, MathHelper::comp("12hello", "0"));
    }

    public function testComp2()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame(0, MathHelper::comp("12hello", "0"));
    }

}
