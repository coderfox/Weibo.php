<?php

namespace WeiboPHP;

include_once 'modules/curl.php';
use Curl\Curl;

/*
 * Authorization Module
 */
class Auth{
    /**
     * get code
     *
     * @param string $user            
     * @param string $pass            
     * @param string $key
     *            app key
     * @param string $uri
     *            redirect uri
     * @return string
     */
    public static function GetCode($user, $pass, $key, $uri){
        $curl = new Curl();
        $curl->setReferrer( Auth::ConstructUrl( $key, $uri ) );
        $curl->post( 'https://api.weibo.com/oauth2/authorize', array (
                'client_id' => $key,
                'redirect_uri' => $uri,
                'action' => 'submit',
                'response_type' => 'code',
                'isLoginSina' => '0',
                'from' => '',
                'regCallback' => '',
                'state' => '',
                'ticket' => '',
                'withOfficalFlag' => '0',
                'userId' => $user,
                'passwd' => $pass 
        ) );
        if ($curl -> http_status_code == '301' || $curl -> http_status_code == '302') {
            $loc = $curl -> response_headers ['Location'];
            preg_match( '/code=.{32}/', parse_url( $loc )['query'], $pmatch );
            $code = str_replace( 'code=', '', $pmatch [0] );
            return $code;
        }
    }
    /**
     * get auth url
     *
     * @param string $key
     *            app key
     * @param string $uri
     *            redirect uri
     * @param string $response
     *            code/token
     * @param number $forcelogin
     *            not 0=>force login
     * @param string $scope            
     * @return string
     */
    public static function ConstructUrl($key, $uri, $response = 'code', $forcelogin = 0, $scope = ''){
        $url = "https://api.weibo.com/oauth2/authorize?client_id={$key}&redirect_uri={$uri}&response_type={$response}";
        if ($forcelogin != 0) {
            $url .= '&forcelogin=true';
        }
        if ($scope != '') {
            $url .= "&scope={$scope}";
        }
        return $url;
    }
}