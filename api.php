<?php
/**
 * Sina Weibo Api PHP
 *
 * @author 小傅Fox[<xfox@cotr.me>]
 */
class WeiboEx extends Exception
{

}
class WeiboPHP
{
    /**
     * access token
     *
     * @var string
     */
    private $token = '';
    /**
     * URL prefix {@link https://api.weibo.com/2/}
     *
     * @var string
     */
    private $host = 'https://api.weibo.com/2/';
    public function __construct( $username , $password , $app_key , $r_uri )
    {
        $post_arr = array( 
                'client_id' => $app_key , 
                'redirect_uri' => $r_uri , 
                'action' => 'submit' , 
                'response_type' => 'token' , 
                'isLoginSina' => '0' , 
                'from' => '' , 
                'regCallback' => '' , 
                'state' => '' , 
                'ticket' => '' , 
                'withOfficalFlag' => '0' , 
                'userId' => $username , 
                'passwd' => $password 
        );
        $header = array( 
                'Referer: https://api.weibo.com/oauth2/authorize?client_id=' . $app_key . '&redirect_uri=' . $r_uri 
        );
        $r = WeiboPHP_cURL_Post( 'https://api.weibo.com/oauth2/authorize' , $post_arr , $header , 2 );
        if( strpos( $r , 'HTTP/1.1 200 OK' ) ){
            $r_uri = str_replace( '/' , '\/' , $r_uri );
            preg_match( '/Location: ' . $r_uri . '#access_token=.*&remind_in=/' , $r , $match );
            $r_uri = str_replace( '\/' , '/' , $r_uri );
            $rt = str_replace( 'Location: ' . $r_uri . '#access_token=' , '' , $match[ 0 ] );
            $rt = str_replace( '&remind_in=' , '' , $rt );
            $this -> token = $rt;
        } else{
            throw new WeiboEx( 'Authorize error.' . $r );
        }
    }
    private function HTTPPost( $url , $post , $header )
    {
        $url = $this -> host . $url;
        $post[ 'access_token' ] = $this -> token;
        return json_decode( WeiboPHP_cURL_Post( $url , $post , $header , 0 ) , JSON_OBJECT_AS_ARRAY );
    }
    private function HTTPGet( $url , $get , $header )
    {
        $url = $this -> host . $url;
        $get[ 'access_token' ] = $this -> token;
        return json_decode( WeiboPHP_cURL_Get( $url , $get , $header , 0 ) , JSON_OBJECT_AS_ARRAY );
    }
    public function GetToken()
    {
        return $this -> token;
    }

}