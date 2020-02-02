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
                    if($reserveOriginValue) {
                        continue;
                    }else{
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

}