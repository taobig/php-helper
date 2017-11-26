<?php

namespace taobig\helpers;

class RandomHelper
{

    const CASE_LOWER = 0;
    const CASE_UPPER = 1;

    /**
     * @param int $characterCount
     * @param int $caseFlag
     * @return string
     * @internal param bool $characterCanRepeat
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

}