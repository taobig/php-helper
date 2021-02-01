<?php

namespace taobig\helpers;

use taobig\helpers\exception\io\FileNotFoundException;
use taobig\helpers\exception\io\IOException;

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

    public static function getLastNLines(string $filePath, int $lines = 1, $eol = "\n"): string
    {
        if ($lines <= 0) {
            throw new \ValueError();
        }
        if (strlen($eol) != 1) {
            throw new \ValueError();
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


    public static function getLastNLinesByTailf(string $filePath, int $lines = 1)
    {
        $cmd = "tail -n {$lines} " . escapeshellarg($filePath);
        return shell_exec($cmd);
    }

    public static function readCsvFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new FileNotFoundException($filePath);
        }
//        if (!is_readable($filePath)) {
//            throw new IOException($filePath);
//        }
        $oldErrorHandler = set_error_handler(function ($errno, $str) use ($filePath) {
            if (!(error_reporting() & $errno)) {
                return false;
            }
            throw new IOException($filePath, $str, $errno);
        });
        try {
            $lineList = [];
            if (($handle = fopen($filePath, "r")) !== FALSE) {//fopen: Upon failure, an E_WARNING is emitted.
                try {
                    while (($data = fgetcsv($handle)) !== FALSE) {
                        $lineList[] = $data;
                    }
                } finally {
                    fclose($handle);
                }
                return $lineList;
            } else {
                throw new IOException($filePath);
            }
        } finally {
            set_error_handler($oldErrorHandler);
        }
    }

    public static function writeCsvFile(string $filePath, array $list, string $mode = 'a+'): void
    {
        $oldErrorHandler = set_error_handler(function ($errno, $str) use ($filePath) {
            if (!(error_reporting() & $errno)) {
                return false;
            }
            throw new IOException($filePath, $str, $errno);
        });
        try {
            if (($handle = fopen($filePath, $mode)) !== FALSE) {//fopen: Upon failure, an E_WARNING is emitted.
                try {
                    foreach ($list as $fields) {
                        fputcsv($handle, $fields);
                    }
                } finally {
                    fclose($handle);
                }
            } else {
                throw new IOException($filePath);
            }
        } finally {
            set_error_handler($oldErrorHandler);
        }
    }

}
