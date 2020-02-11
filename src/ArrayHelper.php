<?php

namespace taobig\helpers;

class ArrayHelper
{

    /**
     * @param array $arr
     * @param int $size split by $size
     * @return array
     */
    public static function split(array $arr, int $size): array
    {
        $multiArr = [];
        if ($size <= 0) {
            return $multiArr;
        }

        if ($size >= count($arr)) {
            $multiArr[] = $arr;
        } else {
            $index = 0;
            $length = 0;
            foreach ($arr as $row) {
                ++$length;
                $multiArr[$index][] = $row;
                if ($length >= $size) {
                    ++$index;
                    $length = 0;
                }
            }
        }
        return $multiArr;
    }

    public static function groupBy(array $arr, $column_key): array
    {
        $multiArr = [];
        foreach ($arr as $row) {
            $val = $row[$column_key];
            if (is_int($val) || is_string($val)) {
                $multiArr[$val][] = $row;
            }
        }
        return $multiArr;
    }

    /**
     * convert all keys from underscore-style to camelcase-style.
     * 如果转换后的key已经存在，则使用原始值
     * @param array $arr
     * @param bool $reserveOriginValue
     * @return array
     */
    public static function underscore2camelcase(array $arr, bool $reserveOriginValue = true): array
    {
        $result = [];
        if (is_array($arr)) {
            foreach ($arr as $key => $val) {
                $camelcaseKey = StringHelper::snakeCase2CamelCase($key);
                if (isset($result[$camelcaseKey])) {
                    if ($reserveOriginValue) {
                        continue;
                    } else {
                        $result[$camelcaseKey] = $val;
                    }
                }
                if (!is_array($val)) {
                    $result[$camelcaseKey] = $val;
                } else {
                    $result[$camelcaseKey] = self::underscore2camelcase($val);
                }
            }
        }
        return $result;
    }

    public static function removeEmptyElement(array $arr, bool $discardKeys = false): array
    {
        $result = [];
        foreach ($arr as $key => $item) {
            if (!empty($item)) {
                $result[$key] = $item;
            }
        }
        if ($discardKeys) {
            return array_values($result);
        }
        return $result;
    }

    public static function removeSpecifiedElement(array $arr, $specifiedElement = null, bool $strictType = false, bool $discardKeys = false): array
    {
        $result = [];
        foreach ($arr as $key => $item) {
            if ($strictType) {
                if ($item === $specifiedElement) {
                    continue;
                }
            } else {
                if ($item == $specifiedElement) {
                    continue;
                }
            }
            $result[$key] = $item;
        }
        if ($discardKeys) {
            return array_values($result);
        }
        return $result;
    }

    /**
     * 判断数组是关联数组（associative array）
     * @param array $arr
     * @return bool
     */
    public static function isAssocArray(array $arr): bool
    {
        if (empty($arr)) {
            return true;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * 判断数组是索引数组（indexed array）
     * 1. 包含有合法整型值的字符串会被转换为整型。例如键名 "8" 实际会被储存为 8。但是 "08" 则不会强制转换，因为其不是一个合法的十进制数值。
     * 2. 浮点数也会被转换为整型，意味着其小数部分会被舍去。例如键名 8.7 实际会被储存为 8。
     * 3. 布尔值也会被转换成整型。即键名 true 实际会被储存为 1 而键名 false 会被储存为 0。
     * 4. Null 会被转换为空字符串，即键名 null 实际会被储存为 ""。
     * 5. 数组和对象不能被用为键名。坚持这么做会导致警告：Illegal offset type。
     * 即 $arr[1] === $arr['1'] === $arr[1.5] === $arr[true] === $arr[01]; 但是$arr['01']实际存储的key是字符串'01'。
     * @param array $arr
     * @return bool
     */
    public static function isIndexedArray(array $arr): bool
    {
        if (empty($arr)) {
            return true;
        }
        return array_keys($arr) === range(0, count($arr) - 1);
    }

}