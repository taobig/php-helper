<?php


namespace taobig\helpers\composer;


class Version
{

    public const PACKAGE_ALL = 'package_all';
    public const PACKAGE_DEV = 'package_dev';

    /**
     * @Deprecated
     * @see PACKAGE
     */
    public const PACKAGE_PROD = self::PACKAGE;
    public const PACKAGE = 'package';

    /**
     * @param string $composerLockFile
     * @param string $packageName
     * @param string $floorVersion eg. "1.2.3", NOT "v1.2.3"
     * @param string $packageMode 'all':package and package-dev; 'package'; 'package-dev'
     * @return bool
     */
    public static function checkLocalInstalledVersion(string $composerLockFile, string $packageName, string $floorVersion, string $packageMode = self::PACKAGE): bool
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
            } else if ($packageMode == self::PACKAGE) {
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
            $localInstalledVersion = ltrim($localInstalledVersion, 'v');// remove prefix 'v' if exists, "v1.2.3" => "1.2.3"
            return version_compare($localInstalledVersion, $floorVersion, '>=');
        }
        return false;
    }

}