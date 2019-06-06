<?php

namespace taobig\helpers\lock;

abstract class Lock implements LockInterface
{
    protected $name;
    protected $version;
    protected static $locks = [];

    public function __construct(string $version)
    {
        $this->name = get_called_class();
        $this->version = $version;
    }

    public function name()
    {
        return $this->name;
    }

    protected function getLockKey(string $key)
    {
        return "{$key}[{$this->version}]";
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected function generateLockValue(): int
    {
        return random_int(1, PHP_INT_MAX);
    }

}

