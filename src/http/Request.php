<?php

namespace taobig\helpers\http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Request
{

    /**
     * @param string $url
     * @param float $timeout
     * @param array $option https://docs.guzzlephp.org/en/stable/request-options.html
     * ` $option = [
     *      "headers" => [
     *          "User-Agent" => "...",
     *      ],
     * ]; `
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public static function get(string $url, float $timeout = 1.0, array $option = []): ResponseInterface
    {
        $client = new Client([
            // Base URI is used with relative requests
//            'base_uri' => $url,
            'timeout' => $timeout,
        ]);
        return $client->request('GET', $url, $option);//response body: $response->getBody()->getContents()
    }

    /**
     * @param string $url
     * @param array $params
     * @param float $timeout
     * @param array $option https://docs.guzzlephp.org/en/stable/request-options.html
     * ` $option = [
     *      "headers" => [
     *          "User-Agent" => "...",
     *      ],
     * ]; `
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public static function postJson(string $url, array $params = [], float $timeout = 1.0, array $option = []): ResponseInterface
    {
        return self::post($url, $params, 'application/json', $timeout, $option);
    }

    /**
     * @param string $url
     * @param array $params
     * @param float $timeout
     * @param array $option https://docs.guzzlephp.org/en/stable/request-options.html
     * ` $option = [
     *      "headers" => [
     *          "User-Agent" => "...",
     *      ],
     * ]; `
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public static function postForm(string $url, array $params = [], float $timeout = 1.0, array $option = []): ResponseInterface
    {
        return self::post($url, $params, 'application/x-www-form-urlencoded', $timeout, $option);
    }

    /**
     * @param string $url
     * @param array $params
     * @param float $timeout
     * @param array $option https://docs.guzzlephp.org/en/stable/request-options.html
     * ` $option = [
     *      "headers" => [
     *          "User-Agent" => "...",
     *      ],
     * ]; `
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public static function postFile(string $url, array $params = [], float $timeout = 1.0, array $option = []): ResponseInterface
    {
        return self::post($url, $params, 'multipart/form-data', $timeout, $option);
    }

    /**
     * @param string $url
     * @param array $params
     * @param string $contentType
     * @param float $timeout
     * @param array $option
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private static function post(string $url, array $params, string $contentType, float $timeout, array $option = []): ResponseInterface
    {
        $client = new Client([
            // Base URI is used with relative requests
//            'base_uri' => $url,
            'timeout' => $timeout,
        ]);
        if ($contentType === 'application/json') {
            $option['json'] = $params;
        } else if ($contentType === 'application/x-www-form-urlencoded') {
            $option['form_params'] = $params;
        } else if ($contentType === 'multipart/form-data') {//docs: http://docs.guzzlephp.org/en/stable/request-options.html#multipart
            $option['multipart'] = $params;
        }
        return $client->request('POST', $url, $option);
    }

}