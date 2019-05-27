<?php

namespace taobig\helpers;

class FileHelper
{

    public static function recurseCopy(string $src, string $dst)
    {
        if (file_exists($dst)) {
            if (!is_dir($dst)) {
                throw new \ErrorException("{$dst} has existed, but it not a dir");
            }
        } else {
//            mkdir
//            Emits an E_WARNING level error if the directory already exists.
//            Emits an E_WARNING level error if the relevant permissions prevent creating the directory.
            mkdir($dst, 0777, true);
            if (!is_writable($dst)) {
                throw new \ErrorException("create dir({$dst}) failed");
            }
        }
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $_path = $src . '/' . $file;
                if (is_dir($_path)) {
                    self::recurseCopy($_path, $dst . '/' . $file);
                } else {
                    //If the destination file already exists, it will be overwritten.
                    copy($_path, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public static function recurseRemove(string $src)
    {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $_path = $src . '/' . $file;
                if (is_dir($_path)) {
                    self::recurseRemove($_path);
                } else {
                    unlink($_path);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    public static function getLastNLines(string $filePath, int $lines = 1, $eol = "\n")
    {
        if ($lines <= 0) {
            throw new \OutOfBoundsException('');
        }
        $content = "";
        $resource = fopen($filePath, 'r');
        if ($resource) {
            try {
                fseek($resource, -1, SEEK_END);
                $rowCount = 1;
                do {
                    $str = fgetc($resource);
                    if ($str === false) {
                        break;
                    }
                    if ($str === $eol) {
                        ++$rowCount;
                        if ($rowCount > $lines) {
                            break;
                        }
                    }
                    $content = $str . $content;
                    fseek($resource, -2, SEEK_CUR);
                } while (true);
            } finally {
                fclose($resource);
            }
        }
        return $content;
    }


}