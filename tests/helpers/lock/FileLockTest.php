<?php


use taobig\helpers\lock\exceptions\LockFailedException;
use taobig\helpers\lock\FileLock;

class FileLockTest extends TestCase
{

    public function testLock()
    {
        $fileLock = new FileLock("/tmp/", '1');

        $this->assertSame(FileLock::class, $fileLock->name());
        $this->assertSame(FileLock::class, $fileLock->getName());

        $lockedKey = "testtesttest";
        $lockedValue = $fileLock->lock($lockedKey, 200);
        $this->assertSame(true, is_int($lockedValue));

        $this->expectException(LockFailedException::class);
        $fileLock->lock($lockedKey, 200);
    }

    public function testUnlock()
    {
        $fileLock = new FileLock("/tmp/", '1');

        $lockedKey = "testtesttest";
        $lockedValue = $fileLock->lock($lockedKey, 200);
        $this->assertSame(true, is_int($lockedValue));

        $fileLock->unlock($lockedKey, $lockedValue);

        $fileLock->lock($lockedKey, 200);
    }

}