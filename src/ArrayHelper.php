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

}