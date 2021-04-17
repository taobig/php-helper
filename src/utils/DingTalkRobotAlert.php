<?php

namespace taobig\helpers\utils;

use taobig\helpers\exception\RuntimeException;
use taobig\helpers\http\HttpRequest;

/**
 * docs: https://open-doc.dingtalk.com/microapp/serverapi2/qf2nxq
 * @codeCoverageIgnore
 */
class DingTalkRobotAlert
{
    private string $url;
    private string $secret;

    public function __construct(string $url, string $secret = '')
    {
        $this->url = $url;
        $this->secret = $secret;
    }

    //{
    //    "msgtype": "text",
    //    "text": {
    //        "content": "我就是我, 是不一样的烟火@156xxxx8827"
    //    },
    //    "at": {
    //        "atMobiles": [
    //            "156xxxx8827",
    //            "189xxxx8325"
    //        ],
    //        "isAtAll": false
    //    }
    //}
    //response: {"errcode":0,"errmsg":"ok"}
    //response: {"errcode":130101,"errmsg":"send too fast, exceed 20 times per minute"}
    /**
     * @param string $message
     * @param array $atMobiles
     * @param bool $isAtAll
     * @throws RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function alertText(string $message, array $atMobiles = [], bool $isAtAll = false)
    {
        $url = $this->url;
        $secret = $this->secret;
        if ($secret) {
            //timestamp+"\n"+密钥当做签名字符串
            $timestamp = time() * 1000;//当前时间戳，单位是毫秒，与请求调用时间误差不能超过1小时。
            $content = sprintf("%d\n%s", $timestamp, $secret);
            $str = hash_hmac('sha256', $content, $secret, true);
            $sign = urlencode(base64_encode($str));

            $params = [
                'timestamp' => $timestamp,
                'sign' => $sign,
            ];
            if (strpos($url, '?') === false) {
                $url = $url . '?' . http_build_query($params);
            } else {
                $url = $url . '&' . http_build_query($params);
            }
        }
        $httpClient = new HttpRequest();
        $result = $httpClient->postJson($url, [
            'msgtype' => 'text',
            "text" => [
                "content" => $message,//如果@后的手机号在atMobiles里有，那么消息体里@手机号就是占位符会被显示为@+昵称； 否则就会被当做文本输出。
            ],
            "at" => [
                "atMobiles" => $atMobiles,
                "isAtAll" => $isAtAll
            ]
        ]);
        $arr = json_decode($result, true);
        if ($arr === null || $arr['errcode'] !== 0) {
            if ($arr && isset($arr['errcode']) && $arr['errcode'] == 130101) {
                throw new RuntimeException($arr['errmsg']);
            }
            throw new RuntimeException('send message to dingtalk failed.response:' . $result);
        }
    }
}