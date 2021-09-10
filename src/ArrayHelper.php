<?php

namespace taobig\helpers;

class ArrayHelper
{

    /**
     * @param array $arr
     * @param int $size split by $size
     * @param bool $preserveKeys
     * @return array
     */
    public static function split(array $arr, int $size, bool $preserveKeys = false): array
    {
        $multiArr = [];
        if ($size <= 0) {
            return $multiArr;
        }
//
//        if ($size >= count($arr)) {
//            $multiArr[] = $arr;
//        } else {
//            $index = 0;
//            $length = 0;
//            foreach ($arr as $row) {
//                ++$length;
//                $multiArr[$index][] = $row;
//                if ($length >= $size) {
//                    ++$index;
//                    $length = 0;
//                }
//            }
//        }
//        return $multiArr;
        $multiArr = array_chunk($arr, $size, $preserveKeys);
        return $multiArr;
    }

    /**
     * @param array $arr
     * @param int|string $column_key
     * @return array
     */
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

    /**
     * @param array $arr
     * @param bool $discardKeys
     * @return array
     * @deprecated
     * @see removeEmpty
     */
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

    public static function removeEmpty(array $arr, bool $preserveKeys = true): array
    {
        if ($preserveKeys) {
            return array_filter($arr);
        } else {
            return array_values(array_filter($arr));
        }
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
     * @deprecated
     * @see array_is_list
     */
    public static function isIndexedArray(array $arr): bool
    {
        if (empty($arr)) {
            return true;
        }
        return array_keys($arr) === range(0, count($arr) - 1);
    }

    /**
     * convert object 2 array, will ignore resources and \__PHP_Incomplete_Class
     * @see https://www.php.net/manual/en/function.gettype.php
     * @see https://www.php.net/manual/en/function.is-resource.php
     * @see https://www.php.net/manual/en/function.is-object.php
     * 不能用json_decode(json_encode($obj), true)，因为json_encode不支持resource
     * @param object $obj
     * @return array
     */
    public static function object2Array($obj)
    {
        $arr = (array)$obj;
        foreach ($arr as $k => $v) {
            //is_resource() is not a strict type-checking method: it will return FALSE if var is a resource variable that has been closed.
            if (in_array(gettype($v), ['resource', 'resource (closed)', 'unknown type'])) {
                unset($arr[$k]);
                continue;
            }
            if ($v instanceof \__PHP_Incomplete_Class) {
                unset($arr[$k]);
                continue;
            }
            //7.2.0	is_object() now returns TRUE for unserialized objects without a class definition (class of __PHP_Incomplete_Class). Previously FALSE was returned.
            // >= 7.2.0  gettype($v) => 'object'   and  is_object($v) => true
            // < 7.2.0   gettype($v) => 'object'   and  is_object($v) => false
            if (gettype($v) === 'object' || gettype($v) === 'array') {
                $arr[$k] = self::object2Array($v);
            }
        }
        return $arr;
    }

}