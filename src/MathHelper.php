<?php

namespace taobig\helpers;

use DivisionByZeroError;
use ValueError;

class MathHelper
{

    /**
     * @param string $left_operand
     * @param string $right_operand
     * @throws ValueError
     */
    private static function checkParams(string $left_operand, string $right_operand): void
    {
        // PHP-8.0.2: Fixed bug #80545 (bcadd('a', 'a') doesn't throw an exception).
        // https://www.php.net/ChangeLog-8.php#8.0.2
        // https://bugs.php.net/bug.php?id=80545
        if (PHP_VERSION_ID < 80002) {
            if (trim($left_operand) !== $left_operand) {
                throw new ValueError('bcmath function argument is not well-formed');
            }
            if (trim($right_operand) !== $right_operand) {
                throw new ValueError('bcmath function argument is not well-formed');
            }
            if (!is_numeric($left_operand)) {
                throw new ValueError('bcmath function argument is not well-formed');
            }
            if (!is_numeric($right_operand)) {
                throw new ValueError('bcmath function argument is not well-formed');
            }
        }
    }

    /**
     * return a + b
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
     * return a - b
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
     * return a * b
     * 乘(multiple)（结果超过$scale位的数字将会被舍去）
     * @param string $left_operand
     * @param string $right_operand
     * @param int $scale
     * @return string
     */
    public static function mul(string $left_operand, string $right_operand, int $scale = 2): string
    {
        self::checkParams($left_operand, $right_operand);
        return bcmul($left_operand, $right_operand, $scale);
    }

    /**
     * return a / b
     * 除（结果超过$scale位的数字将会被舍去）
     * @param string $left_operand
     * @param string $right_operand
     * @param int $scale
     * @return string
     * @throws ValueError
     * @throws DivisionByZeroError
     */

    public static function div(string $left_operand, string $right_operand, int $scale = 2): string
    {
        self::checkParams($left_operand, $right_operand);
        if (PHP_VERSION_ID < 80000) {
            $oldErrorHandler = set_error_handler(function ($errno) {
                if (!(error_reporting() & $errno)) {
                    return false;
                }

                throw new DivisionByZeroError("Division by zero", $errno);
            });
            try {
                /**
                 * @psalm-var string $str
                 * @var string $str
                 */
                $str = bcdiv($left_operand, $right_operand, $scale);
                return $str;
            } finally {
                set_error_handler($oldErrorHandler);
            }
        } else {
            /**
             * @psalm-var string $str
             * @var string $str
             */
            $str = bcdiv($left_operand, $right_operand, $scale);
            return $str;
        }
    }

    /**
     * compare a and b, return -1 if a < b, 0 if a = b, 1 if a > b
     * 比较（参数超过$scale位的数字将被忽略不进行比较）
     * @param string $left_operand
     * @param string $right_operand
     * @param int $scale
     * @return int 0 if the two operands are equal,
     * 1 if the <i>left_operand</i> is larger than the <i>right_operand</i>,
     * -1 otherwise.
     */
    public static function comp(string $left_operand, string $right_operand, int $scale = 2): int
    {
        self::checkParams($left_operand, $right_operand);
        return bccomp($left_operand, $right_operand, $scale);
    }

    /**
     * @param string $left_operand
     * @param string $right_operand
     * @param int $scale
     * @return bool
     * @since v2.0.24 以后新增方法不再指定缺省的$scale参数，必须由调用方指定$scale参数
     */
    public static function equals(string $left_operand, string $right_operand, int $scale): bool
    {
        return self::comp($left_operand, $right_operand, $scale) === 0;
    }

    public static function notEquals(string $left_operand, string $right_operand, int $scale): bool
    {
        return self::comp($left_operand, $right_operand, $scale) !== 0;
    }

    public static function isZero(string $operand, int $scale): bool
    {
        return self::comp($operand, '0', $scale) === 0;
    }

    public static function isNotZero(string $operand, int $scale): bool
    {
        return self::comp($operand, '0', $scale) != 0;
    }

    public static function isNegative(string $operand, int $scale): bool
    {
        return self::comp($operand, '0', $scale) === -1;
    }

    public static function isPositive(string $operand, int $scale): bool
    {
        return self::comp($operand, '0', $scale) === 1;
    }

    public static function lessThan(string $left_operand, string $right_operand, int $scale): bool
    {
        return self::comp($left_operand, $right_operand, $scale) === -1;
    }

    public static function lessThanOrEquals(string $left_operand, string $right_operand, int $scale): bool
    {
        $cmp = self::comp($left_operand, $right_operand, $scale);
        return $cmp === -1 || $cmp === 0;
    }

    public static function greaterThan(string $left_operand, string $right_operand, int $scale): bool
    {
        return self::comp($left_operand, $right_operand, $scale) === 1;
    }

    public static function greaterThanOrEquals(string $left_operand, string $right_operand, int $scale): bool
    {
        $cmp = self::comp($left_operand, $right_operand, $scale);
        return $cmp === 1 || $cmp === 0;
    }

}
