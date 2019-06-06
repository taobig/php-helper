<?php

namespace taobig\helpers\lock;

interface LockInterface
{
    public function name();

    public function lock(string $key, int $lifeTime = 60): int;

    /**
     * @param string $key
     * @param int $lockValue
     * @return void
     */
    public function unlock(string $key, int $lockValue);

}


