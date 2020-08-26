<?php


namespace taobig\helpers\composer;


class Version
{

    const PACKAGE_ALL = 'package_all';
    const PACKAGE_DEV = 'package_dev';
    const PACKAGE_PROD = 'package_prod';

    /**
     * @param string $composerLockFile
     * @param string $packageName
     * @param string $floorVersion
     * @param string $packageMode 'all':package and package-dev; 'package'; 'package-dev'
     * @return bool
     */
    public static function checkLocalInstalledVersion(string $composerLockFile, string $packageName, string $floorVersion, string $packageMode = self::PACKAGE_PROD): bool
    {
        $localInstalledVersion = null;
        $composerLockFileContent = file_get_contents($composerLockFile);
        $arr = json_decode($composerLockFileContent, true);
        if (($arr !== false)) {
            if ($packageMode == self::PACKAGE_ALL) {
                $packageList = array_merge($arr['packages'] ?? [], $arr['packages-dev'] ?? []);
                $packageMap = array_column($packageList, NUll, 'name');
                if (isset($packageMap[$packageName])) {
                    $localInstalledVersion = $packageMap[$packageName]['version'];
                }
            } else if ($packageMode == self::PACKAGE_DEV) {
                if (isset($arr['packages-dev']) && is_array($arr['packages-dev'])) {
                    $packageMap = array_column($arr['packages-dev'], NUll, 'name');
                    if (isset($packageMap[$packageName])) {
                        $localInstalledVersion = $packageMap[$packageName]['version'];
                    }
                }
            } else if ($packageMode == self::PACKAGE_PROD) {
                if (isset($arr['packages']) && is_array($arr['packages'])) {
                    $packageMap = array_column($arr['packages'], NUll, 'name');
                    if (isset($packageMap[$packageName])) {
                        $localInstalledVersion = $packageMap[$packageName]['version'];
                    }
                }
            } else {
                return false;
            }
        }
        if ($localInstalledVersion) {
            return version_compare($localInstalledVersion, $floorVersion, '>=');
        }
        return false;
    }

}