<?php

use taobig\helpers\StringEncodingHelper;

class StringEncodingHelperTest extends TestCase
{

    public function testDecodeUnicode()
    {
        $str = "\u4f60\u597d";
        $unicodeStr = (new StringEncodingHelper())->decodeUnicode($str);
        $this->assertSame("你好", $unicodeStr);
    }

}
