<?php

use taobig\helpers\NetHelper;

class NetHelperTest extends TestCase
{

    public function testGetLocalIpV4()
    {
        $count = shell_exec("/sbin/ifconfig -s|wc -l");//include title & "Local Loopback"
        $ipList = NetHelper::getMachineIpV4();
        echo "All ip address\n";
        var_dump($ipList);
        $this->assertSame($count - 1 - 1, count($ipList));


        $publicIpList = NetHelper::getMachineIpV4(true);
        $result = shell_exec("curl ifconfig.co");// ifconfig.me  or ipinfo.io
        $json = json_decode($result, true);
        if (in_array($json['ip'], $ipList)) {//the machine may has more one public ip address
            $this->assertSame(true, in_array($json['ip'], $publicIpList));
        } else {
            $this->assertSame(0, count($publicIpList));
        }
    }

    public function testIsPrivateIP()
    {
        $result = NetHelper::isPrivateIP("10.0.0.0");
        $this->assertSame(true, $result);

        $result = NetHelper::isPrivateIP("192.168.0.0");
        $this->assertSame(true, $result);
        $result = NetHelper::isPrivateIP("192.1.0.0");
        $this->assertSame(false, $result);


        $result = NetHelper::isPrivateIP("172.0.0.0");
        $this->assertSame(false, $result);
        $result = NetHelper::isPrivateIP("172.16.0.0");
        $this->assertSame(true, $result);
        $result = NetHelper::isPrivateIP("172.31.255.255");
        $this->assertSame(true, $result);
        $result = NetHelper::isPrivateIP("172.32.0.0");
        $this->assertSame(false, $result);

    }

}
