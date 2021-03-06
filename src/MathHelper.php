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
        return bcmul($left_operand, $right_operand, $scale);
    }

    /**
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
