<?php

namespace taobig\helpers\lock;

use taobig\helpers\lock\exceptions\LockFailedException;

class FileLock extends Lock
{

    /**
     * @var string
     */
    protected $dirPath;

    public function __construct(string $dirPath, string $version)
    {
        parent::__construct($version);

        $this->dirPath = rtrim($dirPath, '/') . '/';
    }

    public function __destruct()
    {
        foreach (self::$locks as $key => $resource) {
            $this->unlock($key, $resource);
        }
    }

    public function lock(string $key, int $lifeTime = 60): int
    {
        $resource = @fopen($this->dirPath  . md5($key), 'a');
        if (!$resource || !flock($resource, LOCK_EX | LOCK_NB)) {
            throw new LockFailedException($key);
        }
        self::$locks[$key] = $resource;
        return 0;
    }

    /**
     * @param string $key
     * @param int $lockValue
     * @return void
     */
    public function unlock(string $key, int $lockValue)
    {
        if (isset(self::$locks[$key])) {
            $resource = self::$locks[$key];
//            flock($resource, LOCK_UN);
            fclose($resource);
            $resource = null;

            unset(self::$locks[$key]);
        }
    }

}