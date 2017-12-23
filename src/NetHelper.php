<?php

namespace taobig\helpers;

class NetHelper
{

    public static function getMachineIpV4($onlyPublicAddress = false)
    {
        $ipList = [];
        $result = shell_exec("/sbin/ifconfig");
        if (preg_match_all("/addr:(\d+\.\d+\.\d+\.\d+)/", $result, $match) !== 0) {
            foreach ($match[1] as $k => $v) {
                $ipAddress = $match[1][$k];
                if ($ipAddress != "127.0.0.1") {
                    if ($onlyPublicAddress) {
                        if (self::isPrivateIP($ipAddress)) {
                            continue;
                        }
                    }
                    $ipList[] = $ipAddress;
                }
            }
        }
        return $ipList;
    }

    public static function isPrivateIP(string $ipAddress)
    {
        //    10.0.0.0/8：10.0.0.0～10.255.255.255
        //　　172.16.0.0/12：172.16.0.0～172.31.255.255
        //　　192.168.0.0/16：192.168.0.0～192.168.255.255
        if ((strncmp($ipAddress, "10.", 3) == 0) ||
            (strncmp($ipAddress, "192.168.", 8) == 0)) {
            return true;
        }
        if ((strncmp($ipAddress, "172.", 4) == 0)) {
            $arr = explode('.', $ipAddress);
            if ($arr[1] >= 16 && $arr[1] <= 31) {
                return true;
            }
        }
        return false;
    }

}