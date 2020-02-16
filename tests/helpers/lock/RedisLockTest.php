<?php


use taobig\helpers\lock\exceptions\LockFailedException;
use taobig\helpers\lock\RedisExtensionConnection;
use taobig\helpers\lock\RedisLock;
use taobig\helpers\lock\YiiRedisConnection;
use yii\redis\Connection;

class RedisLockTest extends TestCase
{

    public function testLockWithRedisExtensionConnection()
    {
        $redis = new \Redis();
        $redis->connect("localhost");
        $redisConnection = new RedisExtensionConnection($redis);
        foreach ([true, false] as $enableEvalCommand) {
            $redisLock = new RedisLock($redisConnection, '1', $enableEvalCommand);

            var_dump($redisLock->name());
            $this->assertSame(RedisLock::class, $redisLock->getName());

            $lockedKey = "testtesttest_" . time();
            $lockedValue = $redisLock->lock($lockedKey, 200);
            $this->assertSame(true, is_int($lockedValue));

            $this->expectException(LockFailedException::class);
            $redisLock->lock($lockedKey, 200);
        }
    }

    public function testUnlockWithRedisExtensionConnection()
    {
        $redis = new \Redis();
        $redis->connect("localhost");
        $redisConnection = new RedisExtensionConnection($redis);
        foreach ([true, false] as $enableEvalCommand) {
            $redisLock = new RedisLock($redisConnection, '1', $enableEvalCommand);

            $lockedKey = "testtesttest_" . time();;
            $lockedValue = $redisLock->lock($lockedKey, 200);
            $this->assertSame(true, is_int($lockedValue));

            $redisLock->unlock($lockedKey, $lockedValue);

            $redisLock->lock($lockedKey, 200);
        }
    }

    public function testLockWithYiiRedisConnection()
    {
        require(__DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php');

        $redis = new Connection();
        $redisConnection = new YiiRedisConnection($redis);
        foreach ([true, false] as $enableEvalCommand) {
            $redisLock = new RedisLock($redisConnection, '1', $enableEvalCommand);

            var_dump($redisLock->name());
            $this->assertSame(RedisLock::class, $redisLock->getName());

            $lockedKey = "testtesttest_" . time();
            $lockedValue = $redisLock->lock($lockedKey, 200);
            $this->assertSame(true, is_int($lockedValue));

            $this->expectException(LockFailedException::class);
            $redisLock->lock($lockedKey, 200);
        }
    }


    public function testUnlockWithYiiRedisConnection()
    {
        require(__DIR__ . '/../../../vendor/yiisoft/yii2/Yii.php');

        $redis = new Connection();
        $redisConnection = new YiiRedisConnection($redis);
        foreach ([true, false] as $enableEvalCommand) {
            $redisLock = new RedisLock($redisConnection, '1', $enableEvalCommand);

            $lockedKey = "testtesttest_" . time();;
            $lockedValue = $redisLock->lock($lockedKey, 200);
            $this->assertSame(true, is_int($lockedValue));

            $redisLock->unlock($lockedKey, $lockedValue);

            $redisLock->lock($lockedKey, 200);
        }
    }

}