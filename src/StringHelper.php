<?php

namespace taobig\helpers;

class StringHelper
{

    /**
     * All the parameters are case-sensitive.
     * @param string $str
     * @param string $characters
     * @return bool
     */
    public static function startsWith(string $str, string $characters): bool
    {
        if (strlen($str) < strlen($characters)) {
            return false;
        }
        return substr_compare($str, $characters, 0, strlen($characters)) === 0;
    }

    /**
     * All the parameters are case-sensitive.
     * @param string $str
     * @param string $characters
     * @return bool
     */
    public static function endsWith(string $str, string $characters): bool
    {
        if (strlen($str) < strlen($characters)) {
            return false;
        }
        if (strlen($characters) === 0) {
            return true;
        }
        return substr_compare($str, $characters, -strlen($characters)) === 0;
    }

    /**
     * All the parameters are case-sensitive.
     * @param string $str
     * @param string $characters
     * @return bool|string
     */
    public static function stripLeft(string $str, string $characters)
    {
        if (self::startsWith($str, $characters)) {
            return substr($str, strlen($characters));
        }
        return $str;
    }

    /**
     * All the parameters are case-sensitive.
     * @param string $str
     * @param string $characters
     * @return bool|string
     */
    public static function stripRight(string $str, string $characters)
    {
        if (self::endsWith($str, $characters)) {
            return substr($str, 0, strlen($str) - strlen($characters));
        }
        return $str;
    }

}