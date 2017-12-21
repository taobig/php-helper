<?php

use taobig\helpers\FileHelper;

class FileHelperTest extends TestCase
{

    public function testCopy()
    {
        $sourceDir = __DIR__;
        $dstDir = __DIR__ . '/../../test_' . time();
        FileHelper::recurseCopy($sourceDir, $dstDir);

        $sourceFiles = [];
        foreach (glob($sourceDir . "/*.php") as $filename) {
            $sourceFiles[] = substr($filename, strlen($sourceDir) + 1);
        }
        foreach (glob($sourceDir . "/*/*.php") as $filename) {
            $sourceFiles[] = substr($filename, strlen($sourceDir) + 1);
        }

        $dstDir = realpath($dstDir);
        $dstFiles = [];
        foreach (glob($dstDir . "/*.php") as $filename) {
            $dstFiles[] = substr($filename, strlen($dstDir) + 1);
        }
        foreach (glob($dstDir . "/*/*.php") as $filename) {
            $dstFiles[] = substr($filename, strlen($dstDir) + 1);
        }

        $this->assertSame([], array_diff($sourceFiles, $dstFiles));
        $this->assertSame([], array_diff($dstFiles, $sourceFiles));

        return $dstDir;
    }

    /**
     * @depends testCopy
     */
    public function testRemove($dir)
    {
        FileHelper::recurseRemove($dir);
        $this->assertSame(false, file_exists($dir));
    }

}
