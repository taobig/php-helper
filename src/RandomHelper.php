<?php

namespace taobig\helpers;

class RandomHelper
{

    /**
     * @deprecated
     */
    const CASE_LOWER = 0;
    /**
     * @deprecated
     */
    const CASE_UPPER = 1;

    public const DIGITS = 1 << 0;//bin:0001
    public const LOWER_CASE_ALPHABET = 1 << 1;//bin:0010
    public const UPPER_CASE_ALPHABET = 1 << 2;//bin:0100


    /**
     * @param int $characterCount
     * @param int $caseFlag
     * @return string
     * @throws \Exception
     * @see StringHelper::random()
     * @deprecated
     */
    public function getRandomEnglishCharacters(int $characterCount, int $caseFlag = self::CASE_LOWER)
    {
        // ASCII
        // 65-90 => A-Z
        // 97–122 => a–z
        $str = '';
        for ($i = 0; $i < $characterCount; ++$i) {
            if ($caseFlag === self::CASE_LOWER) {
                $iVal = random_int(97, 122);
            } else {
                $iVal = random_int(65, 90);
            }
            $str .= chr($iVal);
        }

        return $str;
    }


    /**
     * 生成一个可选包含大写英文字母、小写英文字母、数字的字符串
     * @param int $length
     * @param int $numType eg. self::DIGITS,self::LOWER_CASE_ALPHABET,self::UPPER_CASE_ALPHABET,self::DIGITS|self::LOWER_CASE_ALPHABET,...
     * @return string
     */
    public static function str(int $length, int $numType): string
    {
        $arr = [];
        if ($numType & self::DIGITS) {
            $arr = array_merge($arr, range(0, 9));
        }
        if ($numType & self::LOWER_CASE_ALPHABET) {
            $arr = array_merge($arr, range('a', 'z'));
        }
        if ($numType & self::UPPER_CASE_ALPHABET) {
            $arr = array_merge($arr, range('A', 'Z'));
        }
        if (empty($arr)) {
            throw new \InvalidArgumentException();
        }
        $str = '';
        $arr_len = count($arr);
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $arr_len - 1);
            $str .= $arr[$rand];
        }
        return $str;
    }

}