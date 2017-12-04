<?php

namespace taobig\helpers;


class ErrorHandler
{

    public function init()
    {
        error_reporting(E_ALL);
//        error_reporting(0);//将会关闭所有的错误
        ini_set("display_errors", 0);

        set_error_handler(function ($errno, $str, $file, $line) {
            throw new \ErrorException($str, 0, $errno, $file, $line);
        });
//        set_exception_handler(function (\Throwable $exception) {});
    }
}