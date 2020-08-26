<?php


namespace taobig\helpers\composer;


class Version
{

    /**
     * @param string $composerLockFile
     * @param string $packageName
     * @param string $floorVersion
     * @return bool
     */
    public static function checkLocalInstalledVersion(string $composerLockFile, string $packageName, string $floorVersion): bool
    {
        $composerLockFileContent = file_get_contents($composerLockFile);
        $arr = json_decode($composerLockFileContent, true);
        if (($arr !== false) && isset($arr['packages']) && is_array($arr['packages'])) {
            $packageMap = array_column($arr['packages'], NUll, 'name');
            if (isset($packageMap[$packageName])) {
                $localInstalledVersion = $packageMap[$packageName]['version'];
                return version_compare($localInstalledVersion, $floorVersion, '>=');
            }
        }
        return false;
    }

}