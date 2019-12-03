<?php

namespace taobig\helpers\utils;

use RuntimeException;
use taobig\helpers\http\HttpRequest;

/**
 * docs: https://open-doc.dingtalk.com/microapp/serverapi2/qf2nxq
 * @codeCoverageIgnore
 */
class DingTalkRobotAlert
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
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
    public function alertText(string $message, array $atMobiles = [], $isAtAll = false)
    {
        $httpClient = new HttpRequest();
        $result = $httpClient->postJson($this->url, [
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
            throw new RuntimeException('send message to dingtalk failed.response:' . $result);
        }
    }
}