<?php

declare(strict_types=1);

namespace taobig\tests\helpers\helpers;

use taobig\helpers\MathHelper;

class MathHelperTest extends \TestCase
{

    //一个方法里只能有一个expectException
    public function testAdd()
    {
        $this->assertSame("3.00", MathHelper::add("1", "2"));
        $this->assertSame("3.13", MathHelper::add("1.01", "2.12"));
        $this->assertSame("1.11", MathHelper::add("-1.01", "2.12"));
        $this->assertSame("1.11", MathHelper::add("2.12", "-1.01"));

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
    }

    public function testAdd4()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame("2.00", MathHelper::add("12hello", "2"));
    }

    public function testAdd5()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame("2.00", MathHelper::add("12", "2 "));
    }

    public function testAdd6()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame("2.00", MathHelper::add("12", "2hello"));
    }

    public function testSub()
    {
        $this->assertSame("3.00", MathHelper::sub("5", "2"));
        $this->assertSame("4.98", MathHelper::sub("5.00", "0.02"));
        $this->assertSame("2.01", MathHelper::sub("5.11", "3.10"));
        $this->assertSame("98.52", MathHelper::sub("100.53", "2.01"));
        $this->assertSame("102.54", MathHelper::sub("100.53", "-2.01"));
        $this->assertSame("-98.52", MathHelper::sub("-100.53", "-2.01"));
        $this->assertSame("-102.54", MathHelper::sub("-100.53", "2.01"));
    }

    public function testSubException()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame("3.00", MathHelper::sub(" 5", "2"));
    }

    public function testMul()
    {
        $this->assertSame("6.0000", MathHelper::mul("2", "3", 4));
        $this->assertNotSame("6", MathHelper::mul("2", "3"));

        $this->assertSame("7.26000", MathHelper::mul("2.2", "3.3", 5));

        $this->assertSame("6.00", MathHelper::mul("2", "3"));
        $this->assertSame("3.33", MathHelper::mul("1.1111111", "3"));
    }

    public function testMulException()
    {
        $this->expectException(\ValueError::class);
        $this->assertSame("6.0000", MathHelper::mul(" 2", "3", 4));
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

    public function testEquals()
    {
        $this->assertSame(false, MathHelper::equals("12", "0", 2));
        $this->assertSame(true, MathHelper::equals("0", "0", 2));
        $this->assertSame(true, MathHelper::equals("888", "888", 2));
        $this->assertSame(true, MathHelper::equals("888.12", "888.12", 2));
        $this->assertSame(true, MathHelper::equals("888.12", "888.12345", 2));
        $this->assertSame(false, MathHelper::equals("888.12", "888.12345", 3));
        $this->assertSame(false, MathHelper::equals("0", "011", 2));
    }

    public function testNotEquals()
    {
        $this->assertSame(true, MathHelper::notEquals("12", "0", 2));
        $this->assertSame(false, MathHelper::notEquals("0", "0", 2));
        $this->assertSame(false, MathHelper::notEquals("888", "888", 2));
        $this->assertSame(false, MathHelper::notEquals("888.12", "888.12", 2));
        $this->assertSame(false, MathHelper::notEquals("888.12", "888.12345", 2));
        $this->assertSame(true, MathHelper::notEquals("888.12", "888.12345", 3));
        $this->assertSame(true, MathHelper::notEquals("0", "011", 2));
    }

    public function testIsZero()
    {
        $this->assertSame(false, MathHelper::isZero("12", 2));
        $this->assertSame(true, MathHelper::isZero("0", 2));
        $this->assertSame(true, MathHelper::isZero("0.00", 2));
        $this->assertSame(true, MathHelper::isZero("0.00888", 2));
        $this->assertSame(false, MathHelper::isZero("0.00888", 3));
    }

    public function testIsNotZero()
    {
        $this->assertSame(true, MathHelper::isNotZero("12", 2));
        $this->assertSame(false, MathHelper::isNotZero("0", 2));
        $this->assertSame(false, MathHelper::isNotZero("0.00", 2));
        $this->assertSame(false, MathHelper::isNotZero("0.00888", 2));
        $this->assertSame(true, MathHelper::isNotZero("0.00888", 3));
    }

    public function testIsNegative()
    {
        $this->assertSame(false, MathHelper::isNegative("12", 2));
        $this->assertSame(false, MathHelper::isNegative("0", 2));
        $this->assertSame(false, MathHelper::isNegative("-0.00888", 2));
        $this->assertSame(true, MathHelper::isNegative("-0.00888", 3));
        $this->assertSame(false, MathHelper::isNegative("0.00888", 5));
    }

    public function testIsPositive()
    {
        $this->assertSame(true, MathHelper::isPositive("12", 2));
        $this->assertSame(false, MathHelper::isPositive("0", 2));
        $this->assertSame(false, MathHelper::isPositive("-0.00888", 2));
        $this->assertSame(true, MathHelper::isPositive("0.00888", 5));
    }

    public function testLessThan()
    {
        $this->assertSame(false, MathHelper::lessThan("12", "0", 2));
        $this->assertSame(false, MathHelper::lessThan("0", "0", 2));
        $this->assertSame(false, MathHelper::lessThan("888", "888", 2));
        $this->assertSame(false, MathHelper::lessThan("888.12", "888.12", 2));
        $this->assertSame(false, MathHelper::lessThan("888.12", "888.12345", 2));
        $this->assertSame(true, MathHelper::lessThan("888.12", "888.12345", 3));
        $this->assertSame(true, MathHelper::lessThan("0", "011", 2));
        $this->assertSame(false, MathHelper::lessThan("011", "0", 2));
    }

    public function testLessThanOrEquals()
    {
        $this->assertSame(false, MathHelper::lessThanOrEquals("12", "0", 2));
        $this->assertSame(true, MathHelper::lessThanOrEquals("0", "0", 2));
        $this->assertSame(true, MathHelper::lessThanOrEquals("888", "888", 2));
        $this->assertSame(true, MathHelper::lessThanOrEquals("888.12", "888.12", 2));
        $this->assertSame(true, MathHelper::lessThanOrEquals("888.12", "888.12345", 2));
        $this->assertSame(true, MathHelper::lessThanOrEquals("888.12", "888.12345", 3));
        $this->assertSame(false, MathHelper::lessThanOrEquals("888.12345", "888.12", 3));
        $this->assertSame(true, MathHelper::lessThanOrEquals("0", "011", 2));
        $this->assertSame(false, MathHelper::lessThanOrEquals("011", "0", 2));
    }

    public function testGreaterThan()
    {
        $this->assertSame(true, MathHelper::greaterThan("12", "0", 2));
        $this->assertSame(false, MathHelper::greaterThan("0", "0", 2));
        $this->assertSame(false, MathHelper::greaterThan("888", "888", 2));
        $this->assertSame(false, MathHelper::greaterThan("888.12", "888.12", 2));
        $this->assertSame(false, MathHelper::greaterThan("888.12", "888.12345", 2));
        $this->assertSame(false, MathHelper::greaterThan("888.12", "888.12345", 3));
        $this->assertSame(true, MathHelper::greaterThan("888.12345", "888.12", 3));
        $this->assertSame(false, MathHelper::greaterThan("0", "011", 2));
        $this->assertSame(true, MathHelper::greaterThan("011", "0", 2));
    }

    public function testGreaterThanOrEquals()
    {
        $this->assertSame(true, MathHelper::greaterThanOrEquals("12", "0", 2));
        $this->assertSame(true, MathHelper::greaterThanOrEquals("0", "0", 2));
        $this->assertSame(true, MathHelper::greaterThanOrEquals("888", "888", 2));
        $this->assertSame(true, MathHelper::greaterThanOrEquals("888.12", "888.12", 2));
        $this->assertSame(true, MathHelper::greaterThanOrEquals("888.12", "888.12345", 2));
        $this->assertSame(false, MathHelper::greaterThanOrEquals("888.12", "888.12345", 3));
        $this->assertSame(true, MathHelper::greaterThan("888.12345", "888.12", 3));
        $this->assertSame(false, MathHelper::greaterThanOrEquals("0", "011", 2));
        $this->assertSame(true, MathHelper::greaterThanOrEquals("011", "0", 2));
    }

}
