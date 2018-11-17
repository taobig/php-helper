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

    public static function mb_rtrim(string $str, string $trim, string $encoding = 'UTF-8'): string
    {
        $mask = [];
        $trimLength = mb_strlen($trim, $encoding);
        for ($i = 0; $i < $trimLength; ++$i) {
            $item = mb_substr($trim, $i, 1, $encoding);
            $mask[] = $item;
        }

        $len = mb_strlen($str, $encoding);
        $pos = $len;
        if ($len > 0) {
            for ($i = $len - 1; $i >= 0; --$i) {
                $item = mb_substr($str, $i, 1, $encoding);
                if (in_array($item, $mask)) {
                    --$pos;
                } else {
                    break;
                }
            }
            return mb_substr($str, 0, $pos, $encoding);
        }
        return $str;
    }

    /**
     * @deprecated
     * @param string $str
     * @param int $length
     * @param string $paddingChar Must be a character
     * @return string
     */
    public static function leftPadding(string $str, int $length, string $paddingChar): string
    {
        return self::prepend($str, $length, $paddingChar);
    }

    /**
     * @param string $str
     * @param int $length
     * @param string $paddingChar Must be a character
     * @return string
     */
    public static function prepend(string $str, int $length, string $paddingChar): string
    {
        if (strlen($paddingChar) !== 1) {
            return $str;
        }
        if (strlen($str) >= $length) {
            return $str;
        }
        $paddingLength = $length - strlen($str);
        for ($i = 0; $i < $paddingLength; ++$i) {
            $str = "{$paddingChar}{$str}";
        }
        return $str;
    }

}