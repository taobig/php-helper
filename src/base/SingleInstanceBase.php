<?php


namespace taobig\helpers\base;


class SingleInstanceBase {

    protected static $instance;

    private function __construct() {
    }


    private function __clone() {
    }

    /**
     * @return static
     */
    public static function getInstance() {
        if (static::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}