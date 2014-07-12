<?php

namespace WeiboPHP;

include_once 'modules/curl.php';
include_once 'modules/WeiboEx.php';
use Curl\Curl;

class API{
    /**
     * API host
     * @var string
     */
    public static $host = 'https://api.weibo.com/';
    /**
     * query a API without automatically add token
     * @param string $url
     * @param string $method get/post [case sensitive]
     * @param string $param
     * @throws \Exception
     * @return array
     */
    public static function QueryWithoutToken($url, $method = 'get', $param = NULL){
        $curl = new Curl();
        if ($method == 'get') {
            $curl->get( API :: $host . $url, $param );
            if ($curl -> curl_error) {
                throw new \Exception( $curl -> curl_error_message, $curl -> curl_error_code );
            }
            $result = json_decode( $curl -> response, true );
            if (isset( $result ['error'] )) {
                throw WeiboEx::Construct( $result ['error'], $result ['error_code'], $result );
            }
            return $result;
        } elseif ($method == 'post') {
            $curl->post( API :: $host . $url, $param );
            if ($curl -> curl_error) {
                throw new \Exception( $curl -> curl_error_message, $curl -> curl_error_code );
            }
            $result = json_decode( $curl -> response, true );
            if (isset( $result ['error'] )) {
                throw WeiboEx::Construct( $result ['error'], $result ['error_code'], $result );
            }
            return $result;
        } else {
            throw new \Exception( 'invaid method' );
        }
    }
    /**
     * query a API with automatically add token
     * @param string $url
     * @param Token $token
     * @param string $method get/post [case sensitive]
     * @param string $param
     * @throws \Exception
     * @return mixed
     */
    public static function Query($url, $token, $method = 'get', $param = NULL){
        $curl = new Curl();
        $tok = $token->GetToken();
        if ($method == 'get') {
            $curl -> request_headers ['Authorization'] = "OAuth2 {$tok}";
            $curl->get( API :: $host . $url, $param );
            if ($curl -> curl_error) {
                throw new \Exception( $curl -> curl_error_message, $curl -> curl_error_code );
            }
            $result = json_decode( $curl -> response, true );
            if (isset( $result ['error'] )) {
                throw WeiboEx::Construct( $result ['error'], $result ['error_code'], $result );
            }
            return $result;
        } elseif ($method == 'post') {
            $curl -> request_headers ['Authorization'] = "OAuth2 {$tok}";
            $curl->post( API :: $host . $url, $param );
            if ($curl -> curl_error) {
                throw new \Exception( $curl -> curl_error_message, $curl -> curl_error_code );
            }
            $result = json_decode( $curl -> response, true );
            if (isset( $result ['error'] )) {
                throw WeiboEx::Construct( $result ['error'], $result ['error_code'], $result );
            }
            return $result;
        } else {
            throw new \Exception( 'invaid method' );
        }
    }
}