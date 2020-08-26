<?php


namespace taobig\tests\helpers\composer;

use taobig\helpers\composer\Version;
use TestCase;

class VersionTest extends TestCase
{

    public function testCheckLocalInstalledVersion()
    {
        $composerLockFile = __DIR__ . '/../../../composer.lock';
        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '6.3');
        $this->assertSame(true, $b);

        $composerLockFile = __DIR__ . '/../../../composer.lock';
        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '99.99.99');
        $this->assertSame(false, $b);


        $composerLockFile = __DIR__ . '/../../../composer.json';
        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '6.3');
        $this->assertSame(false, $b);
    }

}