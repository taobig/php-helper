<?php

namespace taobig\helpers;

use PHPUnit\Runner\Exception;

class FileHelper
{

    public static function recurseCopy(string $src, string $dst)
    {
        if (!mkdir($dst)) {
            throw new Exception("create dir({$dst}) failed");
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

}