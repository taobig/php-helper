<?php

namespace taobig\helpers;

class MathHelper
{

    /**
     * PHP会把" 1"/"H1"/...转换成0。
     * >=PHP7.4: "Warning: bcadd(): bcmath function argument is not well-formed in ..."
     * @param string $left_operand
     * @param string $right_operand
     * @throws \ErrorException
     */
    private static function checkParams(string $left_operand, string $right_operand)
    {
        if (PHP_VERSION_ID < 70400) {
            if (trim($left_operand) !== $left_operand) {
                throw new \ErrorException('bcadd(): bcmath function argument is not well-formed');
            }
            if (trim($right_operand) !== $right_operand) {
                throw new \ErrorException('bcadd(): bcmath function argument is not well-formed');
            }
            if (!is_numeric($left_operand)) {
                throw new \ErrorException('bcadd(): bcmath function argument is not well-formed');
            }
            if (!is_numeric($right_operand)) {
                throw new \ErrorException('bcadd(): bcmath function argument is not well-formed');
            }
        }
    }

    /**
     * 加（结果超过$scale位的数字将会被舍去）
     * @param string $left_operand
     * @param string $right_operand
     * @param int $scale
     * @return string
     */
    public static function add(string $left_operand, string $right_operand, int $scale = 2): string
    {
        self::checkParams($left_operand, $right_operand);
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
        self::checkParams($left_operand, $right_operand);
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
        self::checkParams($left_operand, $right_operand);
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
        self::checkParams($left_operand, $right_operand);
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
        self::checkParams($left_operand, $right_operand);
        return bccomp($left_operand, $right_operand, $scale);
    }
}
