<?php


namespace taobig\tests\helpers\helpers\composer;

use taobig\helpers\composer\Version;
use TestCase;

class VersionTest extends TestCase
{

    public function testCheckLocalInstalledVersion()
    {
        $composerLockFile = __DIR__ . '/../../../composer.lock';
        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '6.3', Version::PACKAGE_ALL);
        $this->assertSame(true, $b);
        $composerLockFile = __DIR__ . '/../../../composer.lock';
        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '6.3', Version::PACKAGE_PROD);
        $this->assertSame(true, $b);
        $composerLockFile = __DIR__ . '/../../../composer.lock';
        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '6.3', Version::PACKAGE_DEV);
        $this->assertSame(false, $b);

        $composerLockFile = __DIR__ . '/../../../composer.lock';
        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '6.3');
        $this->assertSame(true, $b);

        $composerLockFile = __DIR__ . '/../../../composer.lock';
        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '99.99.99');
        $this->assertSame(false, $b);


        $composerLockFile = __DIR__ . '/../../../composer.lock';
        $b = Version::checkLocalInstalledVersion($composerLockFile, 'phpunit/phpunit', '1.1.1');
        $this->assertSame(false, $b);

        $composerLockFile = __DIR__ . '/../../../composer.lock';
        $b = Version::checkLocalInstalledVersion($composerLockFile, 'phpunit/phpunit', '1.1.1', Version::PACKAGE_DEV);
        $this->assertSame(true, $b);


        $composerLockFile = __DIR__ . '/../../../composer.json';
        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '6.3');
        $this->assertSame(false, $b);
    }

}