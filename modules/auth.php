<?php
include_once 'modules/curl.php';
use Curl\Curl;
/*
 * Authorization Module
 */
class WeiboPHP_Auth{
    public static function High_Permission($user, $pass, $key, $uri){
        $curl=new Curl();
        $curl->setReferrer('https://api.weibo.com/oauth2/authorize?client_id=' .
                $key . '&redirect_uri=' . $uri . '&scope=all&response_type=token');
        $curl->post('https://api.weibo.com/oauth2/authorize',array (
                'client_id' => $key,
                'redirect_uri' => $uri,
                'action' => 'submit',
                'response_type' => 'token',
                'isLoginSina' => '0',
                'from' => '',
                'regCallback' => '',
                'state' => '',
                'ticket' => '',
                'withOfficalFlag' => '0',
                'userId' => $user,
                'passwd' => $pass,
                'scope' => 'all' 
        ));
        if($curl->http_status_code=='301'||$curl->http_status_code=='302'){
            $location=$curl->response_headers['Location'];
            echo $location;
        }
    }
}