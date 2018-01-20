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

}
