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

    public static function underscore2camelcase(array $arr): array
    {
        $result = [];
        if (is_array($arr)) {
            foreach ($arr as $key => $val) {
                $camelcaseKey = lcfirst(implode('', array_map('ucfirst', explode('_', $key))));
                if (isset($result[$camelcaseKey])) {
                    continue;
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
}