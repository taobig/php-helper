<?php

use taobig\helpers\StringEncodingHelper;

class StringEncodingHelperTest extends TestCase
{

    public function testDecodeUnicode()
    {
        $str = "\u4f60\u597d";
        $unicodeStr = (new StringEncodingHelper())->decodeUnicode($str);
        $this->assertSame("你好", $unicodeStr);

        $str = "";
        $unicodeStr = (new StringEncodingHelper())->decodeUnicode($str);
        $this->assertSame("", $unicodeStr);


        $str = " ";
        $unicodeStr = (new StringEncodingHelper())->decodeUnicode($str);
        $this->assertSame(" ", $unicodeStr);

        $str = "[\"\u4f60\u597d\", [\"\u4f60\u597d\"]";
        $unicodeStr = (new StringEncodingHelper())->decodeUnicode($str);
        $this->assertSame("[\"你好\", [\"你好\"]", $unicodeStr);
    }

    public function testToGBK()
    {
        $str = file_get_contents(__DIR__ . '/StringEncodingHelperFile_UCS2BE.source');
        $gbkStr = (new StringEncodingHelper())->toGBK($str);
        $this->assertSame(false, $gbkStr);

        $str = "你好";
        $gbkStr = (new StringEncodingHelper())->toGBK($str);
        $this->assertSame(mb_convert_encoding($str, 'GBK', 'UTF-8'), $gbkStr);
    }

}
