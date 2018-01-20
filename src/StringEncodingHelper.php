<?php

namespace taobig\helpers;

class StringEncodingHelper
{

    /**
     * decode UCS-2 string(eg: \u4f60\u597d ) to human readable characters
     * doc: Linux 和 Windows 平台不同的 UCS-2 编码 https://my.oschina.net/u/249511/blog/98979
     * @param string $str
     * @return null|string
     */
    public static function decodeUnicode(string $str)
    {
        return preg_replace_callback("/\\\u([0-9a-f]{4})/i", function ($matches) {
            return iconv('UCS-2BE', 'UTF-8', pack('H4', $matches[1]));
        }, $str);
    }

}