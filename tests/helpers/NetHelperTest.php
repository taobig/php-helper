<?php

declare(strict_types=1);

namespace taobig\tests\helpers\helpers;

use taobig\helpers\NetHelper;

class NetHelperTest extends \TestCase
{

    public function testGetLocalIpV4()
    {
        if (PHP_VERSION_ID >= 70300) {
            $adapterList = net_get_interfaces();
            print_r($adapterList);
        }
        $ipList = NetHelper::getMachineIpV4();
        echo "All ip address\n";
        var_dump($ipList);

        if (file_exists("/sbin/ifconfig")) {
            var_dump(shell_exec("/sbin/ifconfig | grep 'inet ' | grep -v '127.0.0.1'"));
            //ubuntu 16.04 x86_64: inet addr:172.17.0.1  Bcast:172.17.255.255  Mask:255.255.0.0
            //ubuntu 20.04 x86_64: inet 172.31.0.1  netmask 255.255.0.0  broadcast 172.31.255.255
            var_dump(shell_exec("/sbin/ifconfig | grep 'inet ' | grep -v '127.0.0.1' | grep -Eo '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\s*(Bcast|netmask)'"));
            $count = (int)trim(shell_exec("/sbin/ifconfig | grep 'inet ' | grep -v '127.0.0.1' | grep -Eo '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\s*(Bcast|netmask)'| wc -l"));
            $this->assertSame($count, count($ipList));
        }

        $publicIpList = NetHelper::getMachineIpV4(true);
        $publicIp = shell_exec("curl ifconfig.co");// ifconfig.me  or ipinfo.io  or api.ipify.org  or  ip.cn  or  myip.ipip.net
        if (in_array($publicIp, $ipList)) {
            $this->assertSame(true, in_array($publicIp, $publicIpList));//the machine may has more than one public ip address
        } else {
            $this->assertSame(0, count($publicIpList));
        }
    }

    public function testIsIPv4Address()
    {
        $ip = "127.0.0.1";
        $this->assertSame(true, NetHelper::isIPv4Address($ip));

        $ip = "192.168.0.1";
        $this->assertSame(true, NetHelper::isIPv4Address($ip));

        $ip = "192.168.0.1000";
        $this->assertSame(false, NetHelper::isIPv4Address($ip));

        $ip = "192.168.0.10a";
        $this->assertSame(false, NetHelper::isIPv4Address($ip));

        $ip = "2001:0db8:86a3:08d3:1319:8a2e:0370:7344";
        $this->assertSame(false, NetHelper::isIPv4Address($ip));
    }

    public function testIsPrivateIP()
    {
        $ip = "127.0.0.1";
        $this->assertSame(false, NetHelper::isPrivateIPv4Address($ip));

        $result = NetHelper::isPrivateIPv4Address("10.0.0.0");
        $this->assertSame(true, $result);

        $result = NetHelper::isPrivateIPv4Address("192.168.0.0");
        $this->assertSame(true, $result);
        $result = NetHelper::isPrivateIPv4Address("192.1.0.0");
        $this->assertSame(false, $result);


        $result = NetHelper::isPrivateIPv4Address("172.0.0.0");
        $this->assertSame(false, $result);
        $result = NetHelper::isPrivateIPv4Address("172.16.0.0");
        $this->assertSame(true, $result);
        $result = NetHelper::isPrivateIPv4Address("172.31.255.255");
        $this->assertSame(true, $result);
        $result = NetHelper::isPrivateIPv4Address("172.32.0.0");
        $this->assertSame(false, $result);
    }

    public function testIsPublicIP()
    {
        $ip = "127.0.0.1";
        $this->assertSame(true, NetHelper::isPublicIPv4Address($ip));

        $result = NetHelper::isPublicIPv4Address("10.0.0.0");
        $this->assertSame(false, $result);

        $result = NetHelper::isPublicIPv4Address("192.168.0.0");
        $this->assertSame(false, $result);
        $result = NetHelper::isPublicIPv4Address("192.1.0.0");
        $this->assertSame(true, $result);

        $result = NetHelper::isPublicIPv4Address("172.0.0.0");
        $this->assertSame(true, $result);
        $result = NetHelper::isPublicIPv4Address("172.16.0.0");
        $this->assertSame(false, $result);
        $result = NetHelper::isPublicIPv4Address("172.31.255.255");
        $this->assertSame(false, $result);
        $result = NetHelper::isPublicIPv4Address("172.32.0.0");
        $this->assertSame(true, $result);

        $result = NetHelper::isPublicIPv4Address("112.32.0.22");
        $this->assertSame(true, $result);

    }

}
