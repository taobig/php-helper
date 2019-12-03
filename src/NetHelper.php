<?php

namespace taobig\helpers;

class NetHelper
{

    public static function getMachineIpV4($onlyPublicAddress = false)
    {
        $ipList = [];
        $adapterList = net_get_interfaces();
        foreach ($adapterList as $adapter) {
            foreach ($adapter['unicast'] as $list) {
                if (!isset($list['address'])) {
                    continue;
                }
                $ipAddress = trim($list['address']);
                if (NetHelper::isIPv4Address($ipAddress)) {
                    if ($ipAddress != "127.0.0.1") {//Loop back address
                        if ($onlyPublicAddress) {
                            if (NetHelper::isPrivateIPv4Address($ipAddress)) {
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
        return false;
    }

}