<?php

namespace taobig\helpers\lock;

use taobig\helpers\lock\exceptions\LockFailedException;

class RedisLock extends Lock
{

    protected $enableEvalCommand = true;
    protected $redis;

    public function __construct(RedisConnectionInterface $redis, string $version, bool $enableEvalCommand = true)
    {
        parent::__construct($version);

        $this->redis = $redis;
        $this->enableEvalCommand = $enableEvalCommand;
    }

    public function __destruct()
    {
        foreach (self::$locks as $key => $lockValue) {
            $this->unlock($key, $lockValue);
        }
    }

    /**
     * @param string $key
     * @param int $lifeTime
     * @return int
     * @throws LockFailedException
     * @throws \Exception
     */
    public function lock(string $key, int $lifeTime = 60): int
    {
        $lockKey = $this->getLockKey($key);
        $lockValue = $this->generateLockValue();
        $locked = $this->redis->set($lockKey, $lockValue, ['NX', 'EX' => $lifeTime]);
        if ($locked == true) {
            self::$locks[$key] = $lockValue;
            return $lockValue;
        } else {
            throw new LockFailedException($key);
        }

    }

    /**
     * @param string $key
     * @param int $lockValue
     * @return void
     */
    public function unlock(string $key, int $lockValue)
    {
        $lockKey = $this->getLockKey($key);
        if ($this->enableEvalCommand) {
            $command = <<<COMMAND
if redis.call("get", KEYS[1]) == ARGV[1]
then
    return redis.call("del", KEYS[1])
else
    return 0
end
COMMAND;
            $this->redis->eval($command, [$lockKey, $lockValue], 1);
        } else {
            $lockKey = $this->getLockKey($key);
            $lockValue = $this->redis->get($lockKey);
            if ($lockValue == $lockValue) {
                $this->redis->del([$lockKey]);
            }
        }

        unset(self::$locks[$key]);
    }

}

