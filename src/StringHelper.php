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
        return str_starts_with($str, $characters);
    }

    /**
     * All the parameters are case-sensitive.
     * @param string $str
     * @param string $characters
     * @return bool
     */
    public static function endsWith(string $str, string $characters): bool
    {
        return str_ends_with($str, $characters);
    }

    /**
     * case-sensitive
     * @param string $str
     * @param string $characters
     * @return bool
     */
    public static function contains(string $str, string $characters): bool
    {
        return str_contains($str, $characters);
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
     * @param string $str
     * @param int $length
     * @param string $paddingChar Must be a character
     * @return string
     * @deprecated
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

    //合并多个连续空格为一个
    public static function combineSpaces(string $str): string
    {
        return preg_replace("/\s(?=\s)/", "\\1", $str);
    }

    //合并多个连续空格为一个
    public static function combineSpacesInUtf8String(string $str): ?string
    {
        return preg_replace("/\s(?=\s)/u", "\\1", $str);
    }

    public static function snakeCase2CamelCase(string $str): string
    {
        return lcfirst(implode('', array_map('ucfirst', explode('_', $str))));
    }

    public static function camelCase2KebabCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $input));
    }

    public static function camelCase2SnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    public static function split(string $str, string $separator = ',', bool $keepEmptyParts = true)
    {
        if (PHP_MAJOR_VERSION < 8) {
            if (strlen($separator) === 0) {
                throw new \ValueError("Empty delimiter");
            }
        }
        $list = explode($separator, $str);
        if (!$keepEmptyParts) {
            $validList = [];
            foreach ($list as $item) {
                if ($item === '') {
                    continue;
                }
                $validList[] = $item;
            }
            $list = $validList;
        }
        return $list;
    }


}