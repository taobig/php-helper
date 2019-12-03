<?php

namespace taobig\helpers;

class MathHelper
{

    /**
     * 加（结果超过$scale位的数字将会被舍去）
     * @param string $left_operand
     * @param string $right_operand
     * @param int $scale
     * @return string
     */
    public static function add(string $left_operand, string $right_operand, int $scale = 2): string
    {
        $left_operand = trim($left_operand);
        $right_operand = trim($right_operand);//fix "bcmath function argument is not well-formed" in PHP7.4
        return bcadd($left_operand, $right_operand, $scale);
    }

    /**
     * 减（结果超过$scale位的数字将会被舍去）
     * @param string $left_operand
     * @param string $right_operand
     * @param int $scale
     * @return string
     */
    public static function sub(string $left_operand, string $right_operand, int $scale = 2): string
    {
        $left_operand = trim($left_operand);
        $right_operand = trim($right_operand);//fix "bcmath function argument is not well-formed" in PHP7.4
        return bcsub($left_operand, $right_operand, $scale);
    }

    /**
     * 乘(multiple)（结果超过$scale位的数字将会被舍去）
     * @param string $left_operand
     * @param string $right_operand
     * @param int $scale
     * @return string
     */
    public static function mul(string $left_operand, string $right_operand, int $scale = 2): string
    {
        $left_operand = trim($left_operand);
        $right_operand = trim($right_operand);//fix "bcmath function argument is not well-formed" in PHP7.4
        $result = bcmul($left_operand, $right_operand, $scale);
        if (PHP_VERSION_ID >= 70300) {
            return $result;
        } else {//@see: https://bugs.php.net/bug.php?id=66364
            return bcadd($result, "0", $scale);
        }
    }

    /**
     * 除（结果超过$scale位的数字将会被舍去）
     * @param string $left_operand
     * @param string $right_operand
     * @param int $scale
     * @return string
     */
    public static function div(string $left_operand, string $right_operand, int $scale = 2): string
    {
        $left_operand = trim($left_operand);
        $right_operand = trim($right_operand);//fix "bcmath function argument is not well-formed" in PHP7.4
        return bcdiv($left_operand, $right_operand, $scale);
    }


    /**
     * 比较（参数超过$scale位的数字将被忽略不进行比较）
     * 0 if the two operands are equal, 1 if the
     * <i>left_operand</i> is larger than the
     * <i>right_operand</i>, -1 otherwise.
     *
     * @param string $left_operand
     * @param string $right_operand
     * @param int $scale
     * @return int
     */
    public static function comp(string $left_operand, string $right_operand, int $scale = 2): int
    {
        $left_operand = trim($left_operand);
        $right_operand = trim($right_operand);//fix "bcmath function argument is not well-formed" in PHP7.4
        return bccomp($left_operand, $right_operand, $scale);
    }
}
