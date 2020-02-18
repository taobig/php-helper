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
        return bcmul($left_operand, $right_operand, $scale);
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
        return bccomp($left_operand, $right_operand, $scale);
    }
}
