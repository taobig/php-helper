<?php

use taobig\helpers\FileHelper;

class FileHelperTest extends TestCase
{

    public function testCopyDestinationIsFile()
    {
        $this->expectException(\ErrorException::class);
        $sourceDir = __DIR__;
        $dstDir = '/bin/ls';
        FileHelper::recurseCopy($sourceDir, $dstDir);
    }

    public function testCopyDirCreatedException()
    {
        $this->expectException(\ErrorException::class);

        $old_level = error_reporting(0);
        $old_error_handler = set_error_handler(NULL);
        try {
            $sourceDir = __DIR__;
            $dstDir = '/root/test';
            FileHelper::recurseCopy($sourceDir, $dstDir);
        } catch (\Throwable $e) {
            error_reporting($old_level);
            set_error_handler($old_error_handler);
            throw $e;
        }
    }

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
     * @param string $dir
     */
    public function testRemove(string $dir)
    {
        FileHelper::recurseRemove($dir);
        $this->assertSame(false, file_exists($dir));
    }

    //一个方法里只能有一个expectException
    public function testGetLastNLinesInvalidArgument1()
    {
        $file = __DIR__ . '/fileHelperTest.txt';
        $content = FileHelper::getLastNLines($file, 1);
        $this->assertSame('//last line', $content);

        $content = FileHelper::getLastNLines($file, 2);
        $this->assertSame("//last line but one\n//last line", $content);

        $content = FileHelper::getLastNLines($file, PHP_INT_MAX);
        $this->assertSame(file_get_contents($file), $content);

        $this->expectException(\ValueError::class);
        $content = FileHelper::getLastNLines($file, 0);
        echo $content;//unreachable code
    }

    public function testGetLastNLinesInvalidArgumentException2()
    {
        $file = __DIR__ . '/fileHelperTest.txt';
        $this->expectException(\ValueError::class);
        $content = FileHelper::getLastNLines($file, -1);
        echo $content;//unreachable code
    }

    public function testGetLastNLinesInvalidArgumentException3()
    {
        $file = __DIR__ . '/fileHelperTest.txt';
        $this->expectException(\ValueError::class);
        $content = FileHelper::getLastNLines($file, 1, '');
        echo $content;//unreachable code
    }

    public function testGetLastNLinesInvalidArgumentException4()
    {
        $file = __DIR__ . '/fileHelperTest.txt';
        $this->expectException(\ValueError::class);
        $content = FileHelper::getLastNLines($file, 1, '\r\n');
        echo $content;//unreachable code
    }

    public function testGetLastNLinesByTailf()
    {
        $file = __DIR__ . '/fileHelperTest.txt';
        $content = FileHelper::getLastNLinesByTailf($file, 1);
        $this->assertSame('//last line', $content);
    }

}
