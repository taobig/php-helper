<?php

use taobig\helpers\NetHelper;

class NetHelperTest extends TestCase
{

    public function testGetLocalIpV4()
    {
        $count = trim(shell_exec("/sbin/ifconfig -s|wc -l"));//include title & "Local Loopback"
        $ipList = NetHelper::getMachineIpV4();
        echo "All ip address\n";
        var_dump($ipList);
        $this->assertSame($count - 1 - 1, count($ipList));


        $publicIpList = NetHelper::getMachineIpV4(true);
        $publicIp = shell_exec("curl ifconfig.co");// ifconfig.me  or ipinfo.io  or api.ipify.org  or  ip.cn  or  myip.ipip.net
        if (in_array($publicIp, $ipList)) {
            $this->assertSame(true, in_array($publicIp, $publicIpList));//the machine may has more one public ip address
        } else {
            $this->assertSame(0, count($publicIpList));
        }
    }

    public function testIsPrivateIP()
    {
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

}
