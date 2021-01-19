<?php

namespace taobig\helpers;


class ErrorHandler
{

    public function init()
    {
        error_reporting(E_ALL);
//        error_reporting(0);//将会关闭所有的错误
        ini_set("display_errors", 0);

        set_error_handler(function (int $errno, string $str, string $file = '', int $line = 0) {
            //when @ is used
//            if (error_reporting() == 0) {//< PHP-8.0.0, error_reporting()=0
            if (!(error_reporting() & $errno)) {//>= PHP-8.0.0, error_reporting()>0
                return false; //Silenced
            }
            throw new \ErrorException($str, 0, $errno, $file, $line);
        });
//        @trigger_error("@ operator");
//        trigger_error("Hello");
//        set_exception_handler(function (\Throwable $exception) {});
    }
}