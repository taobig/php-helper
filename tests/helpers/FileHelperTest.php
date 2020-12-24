<?php

declare(strict_types=1);

namespace taobig\tests\helpers\helpers;

use taobig\helpers\exception\io\FileNotFoundException;
use taobig\helpers\exception\io\IOException;
use taobig\helpers\FileHelper;

class FileHelperTest extends \TestCase
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

    public function testReadCsvFile(): string
    {
        $filePath = __DIR__ . '/csv_read_test.csv';
        $lineList = FileHelper::readCsvFile($filePath);
        $this->assertSame('a1', $lineList[0][0]);
        $this->assertSame('a4', $lineList[0][3]);
        $this->assertSame('b3,b33', $lineList[1][2]);
        $this->assertSame('c4,c44\",c444', $lineList[2][3]);
        $this->assertSame('d5",d55', $lineList[3][4]);
        $this->assertSame(4, count($lineList));

        return $filePath;
    }

    public function testReadCsvFileNotFoundException()
    {
        $this->expectException(FileNotFoundException::class);
        $filePath = __DIR__ . '/file_not_exists.txt';
        $lineList = FileHelper::readCsvFile($filePath);
    }

    public function testReadCsvFileReadException()
    {
        $filePath = '';
        if (PHP_OS_FAMILY === 'Darwin') {
            $filePath = '/.file';
        } else if (PHP_OS_FAMILY === 'Linux') {
            $filePath = '/root/.bashrc';
        } else if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped("Todo");
        } else {
            $this->markTestSkipped("Todo");
        }
        $this->expectException(IOException::class);
        $lineList = FileHelper::readCsvFile($filePath);
    }

    /**
     * @depends testReadCsvFile
     * @param string $csvFilePath
     */
    public function testWriteCsvFile(string $csvFilePath)
    {
        $list = array(
            array(
                0 => 'a1',
                1 => 'a2',
                2 => 'a3',
                3 => 'a4',
            ),
            array(
                0 => 'b1',
                1 => 'b2',
                2 => 'b3,b33',
                3 => 'b4',
            ),
            array(
                0 => 'c1',
                1 => 'c2',
                2 => 'c3',
                3 => 'c4,c44\\",c444',
            ),
            array(
                0 => 'd1',
                1 => 'd2',
                2 => 'd3',
                3 => 'd4',
                4 => 'd5",d55',
            ),
        );
        $filePath = __DIR__ . '/csv_write_test.csv';
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        FileHelper::writeCsvFile($filePath, $list);
        $a1 = file_get_contents($csvFilePath);
        $a2 = file_get_contents($filePath);
        $this->assertSame(true, $a1 === $a2);
        unlink($filePath);
    }

    public function testWriteCsvFileException()
    {
        $filePath = '';
        if (PHP_OS_FAMILY === 'Darwin') {
            $filePath = '/.file';
        } else if (PHP_OS_FAMILY === 'Linux') {
            $filePath = '/root/.bashrc';
        } else if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped("Todo");
        } else {
            $this->markTestSkipped("Todo");
        }
        $this->expectException(IOException::class);
        FileHelper::writeCsvFile($filePath, []);
    }

}
