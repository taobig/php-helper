<?php

namespace taobig\helpers\lock;

use taobig\helpers\lock\exceptions\LockFailedException;

interface LockInterface
{
    public function name();

    /**
     * @param string $key
     * @param int $lifeTime
     * @return int
     * @throws LockFailedException
     */
    public function lock(string $key, int $lifeTime = 60): int;

    /**
     * @param string $key
     * @param int $lockValue
     * @return void
     */
    public function unlock(string $key, int $lockValue);

}


