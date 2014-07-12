<?php

namespace WeiboPHP;

include_once 'modules/auth.php';
include_once 'modules/api.php';
include_once 'modules/WeiboEx.php';
use WeiboPHP\Auth;
use WeiboPHP\API;
/*
 * Token object
 */
class Token{
    private $token;
    /**
     *
     * @param string $tok
     *            access token
     */
    public function __construct($tok){
        if (is_string( $tok )) {
            $this -> token = $tok;
        } else {
            throw new \Exception( '$tok not a string', 0 );
        }
    }
    /**
     * get token
     *
     * @param string $user
     *            username
     * @param string $pass
     *            password
     * @param string $key
     *            app key
     * @param string $uri
     *            redirect uri
     * @param string $sec
     *            app secret
     * @return \WeiboPHP\Token
     */
    public static function GetTokenFromPassword($user, $pass, $key, $uri, $sec){
        $code = Auth::GetCode( $user, $pass, $key, $uri );
        return Token::GetTokenFromCode( $code, $key, $uri, $sec );
    }
    /**
     * get token
     *
     * @param string $code
     *            authorization code
     * @param string $key
     *            app key
     * @param string $uri
     *            redirect uri
     * @param string $sec
     *            app secret
     * @return \WeiboPHP\Token
     */
    public static function GetTokenFromCode($code, $key, $uri, $sec){
        $result = API::QueryWithoutToken( 'oauth2/access_token', 'post', array (
                'client_id' => $key,
                'client_secret' => $sec,
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $uri 
        ) );
        return new Token( $result ['access_token'] );
    }
    public function GetToken(){
        return $this -> token;
    }
    public function GetInfo(){
        return API::QueryWithoutToken( 'oauth2/get_token_info', 'post', array (
                'access_token' => $this -> token 
        ) );
    }
    public function Revoke(){
        $result = API::QueryWithoutToken( 'oauth2/revokeoauth2', 'get', array (
                'access_token' => $this -> token 
        ) );
        if ($result ['result'] != 'true') {
            throw new WeiboEx( 'revoke fail', 0 );
        }
    }
}