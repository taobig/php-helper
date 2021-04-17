<?php

// PHP 8.1: New array_is_list function
if (!function_exists('array_is_list')) {
    function array_is_list(array $arr): bool
    {
        if (empty($arr)) {
            return true;
        }
        return array_keys($arr) === range(0, count($arr) - 1);
    }
}
