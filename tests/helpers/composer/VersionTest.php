<?php

declare(strict_types=1);


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

        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '6.3', Version::PACKAGE);
        $this->assertSame(true, $b);

        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '6.3', Version::PACKAGE_DEV);
        $this->assertSame(false, $b);

        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '6.3');
        $this->assertSame(true, $b);

        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '99.99.99');
        $this->assertSame(false, $b);

        $b = Version::checkLocalInstalledVersion($composerLockFile, 'guzzlehttp/guzzle', '6.3');
        $this->assertSame(true, $b);

        $b = Version::checkLocalInstalledVersion($composerLockFile, 'phpunit/phpunit', '1.1.1');
        $this->assertSame(false, $b);

        $b = Version::checkLocalInstalledVersion($composerLockFile, 'phpunit/phpunit', '1.1.1', Version::PACKAGE_DEV);
        $this->assertSame(true, $b);


        {// php-coveralls/php-coveralls v2.5.2
            $b = Version::checkLocalInstalledVersion($composerLockFile, 'php-coveralls/php-coveralls', '2.5.3');
            $this->assertSame(false, $b);

            $b = Version::checkLocalInstalledVersion($composerLockFile, 'php-coveralls/php-coveralls', '2.5.2');
            $this->assertSame(false, $b);

            $b = Version::checkLocalInstalledVersion($composerLockFile, 'php-coveralls/php-coveralls', '2.5.3', Version::PACKAGE_DEV);
            $this->assertSame(false, $b);

            $b = Version::checkLocalInstalledVersion($composerLockFile, 'php-coveralls/php-coveralls', '2.5.2', Version::PACKAGE_DEV);
            $this->assertSame(true, $b);
        }
    }

}