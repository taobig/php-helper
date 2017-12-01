<?php

namespace taobig\helpers\http;

use GuzzleHttp\Client;

class HttpRequest
{

    public function get(string $url, float $timeout = 1.0)
    {
        $client = new Client([
            // Base URI is used with relative requests
//            'base_uri' => $url,
            'timeout' => $timeout,
        ]);
        $response = $client->request('GET', $url);
        return $response->getBody()->getContents();

    }

    public function postJson(string $url, array $params = [], float $timeout = 1.0)
    {
        return self::post($url, $params, 'application/json', $timeout);
    }

    public function postForm(string $url, array $params = [], float $timeout = 1.0)
    {
        return self::post($url, $params, 'application/x-www-form-urlencoded', $timeout);
    }

    //TODO
    private function postFile(string $url, array $params = [], float $timeout = 1.0)
    {
        return self::post($url, $params, 'multipart/form-data', $timeout);
    }

    private function post(string $url, array $params, string $contentType, float $timeout)
    {
        $client = new Client([
            // Base URI is used with relative requests
//            'base_uri' => $url,
            'timeout' => $timeout,
        ]);
        $option = [];
        if ($contentType === 'application/json') {
            $option ['json'] = $params;
        } else if ($contentType === 'application/x-www-form-urlencoded') {
            $option ['form_params'] = $params;
        } else if ($contentType === 'multipart/form-data') {
            $option ['multipart'] = $params;
        }
        $response = $client->request('POST', $url, $option);
        $content = $response->getBody()->getContents();
        return $content;
    }

}