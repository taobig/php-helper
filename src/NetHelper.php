<?php

namespace taobig\helpers;

class NetHelper
{

    public static function getMachineIpV4($onlyPublicAddress = false)
    {
        $ipList = [];
        if (PHP_VERSION_ID >= 70300) {
            $adapterList = net_get_interfaces();
            foreach ($adapterList as $adapter) {
                foreach ($adapter['unicast'] as $list) {
                    if (!isset($list['address'])) {
                        continue;
                    }
                    $ipAddress = trim($list['address']);
                    if (NetHelper::isIPv4Address($ipAddress)) {
                        if (!NetHelper::isPrivateIPv4Address($ipAddress)) {
                            $ipList[] = $ipAddress;
                        }
                    }
                }
            }
        } else {
            $result = shell_exec("/sbin/ifconfig");
            if (preg_match_all("/addr:(\d+\.\d+\.\d+\.\d+)/", $result, $match) !== 0) {
                foreach ($match[1] as $k => $v) {
                    $ipAddress = $match[1][$k];
                    if ($ipAddress != "127.0.0.1") {
                        if ($onlyPublicAddress) {
                            if (self::isPrivateIPv4Address($ipAddress)) {
                                continue;
                            }
                        }
                        $ipList[] = $ipAddress;
                    }
                }
            }
        }
        return $ipList;
    }

    public static function isIPv4Address(string $ipAddress)
    {
        if (preg_match_all("/^(\d+\.\d+\.\d+\.\d+)$/", $ipAddress, $match) !== 0) {
            return $match;
        }
        return false;
    }

    public static function isPrivateIPv4Address(string $ipAddress)
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
        if ($ipAddress === '127.0.0.1') {//Loop back address
            return true;
        }
        return false;
    }

}