<?php
/**
 * Sina Weibo Api PHP
 *
 * @author 小傅Fox[<xfox@cotr.me>]
 */
include_once './curl.php';
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
    /**
     * 构造函数
     *
     * @param string $username
     *            用户名
     * @param string $password
     *            密码
     * @param int $app_key
     *            App Key
     * @param string $r_uri
     *            redirect_uri
     *            
     * @throws WeiboEx
     */
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
                'Referer' => 'https://api.weibo.com/oauth2/authorize?client_id=' . $app_key . '&redirect_uri=' . $r_uri 
        );
        $r = WeiboPHP_cURL_Post( 'https://api.weibo.com/oauth2/authorize' , $post_arr , $header , 2 );
        if( ! strpos( $r , 'HTTP/1.1 200 OK' ) ){
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
    private function HTTPPost( $url , $post , $header = NULL )
    {
        $url = $this -> host . $url;
        $post[ 'access_token' ] = $this -> token;
        $r = json_decode( WeiboPHP_cURL_Post( $url , $post , $header , 0 ) , true );
        if( json_last_error() > 0 ){
            throw new Exception( 'Json Error' . json_last_error() );
        }
        if( isset( $r[ 'error_code' ] ) ){
            throw new Exception( $r[ 'error' ] , $r[ 'error_code' ] );
        }
        return $r;
    }
    private function HTTPGet( $url , $get , $header = NULL )
    {
        $url = $this -> host . $url;
        $get[ 'access_token' ] = $this -> token;
        $r = json_decode( WeiboPHP_cURL_Get( $url , $get , $header , 0 ) , true );
        if( json_last_error() > 0 ){
            throw new Exception( 'Json Error' . json_last_error() );
        }
        if( isset( $r[ 'error_code' ] ) ){
            throw new Exception( $r[ 'error' ] , $r[ 'error_code' ] );
        }
        return $r;
    }
    /**
     * 获取Access Token
     *
     * @return string
     */
    public function GetToken()
    {
        return $this -> token;
    }
    /**
     * 返回最新的200条公共微博，返回结果非完全实时
     * Wiki:{@link http://open.weibo.com/wiki/2/statuses/public_timeline}
     *
     * @param int $count
     *            单页返回的记录条数，最大不超过200，默认为20。
     *            
     * @return array
     */
    public function statuses__public_timeline( $count = 20 )
    {
        $get = array( 
                'count' => $count 
        );
        return $this -> HTTPGet( 'statuses/public_timeline.json' , $get );
    }
    /**
     * 获取当前登录用户及其所关注用户的最新微博
     * Wiki:{@link http://open.weibo.com/wiki/2/statuses/friends_timeline}
     *
     * @param int $since_id
     *            若指定此参数，则返回ID比since_id大的微博（即比since_id时间晚的微博），默认为0。
     * @param int $max_id
     *            若指定此参数，则返回ID小于或等于max_id的微博，默认为0。
     * @param int $count
     *            单页返回的记录条数，最大不超过100，默认为20。
     * @param int $page
     *            返回结果的页码，默认为1。
     * @param int $base_app
     *            是否只获取当前应用的数据。0为否（所有数据），1为是（仅当前应用），默认为0。
     * @param int $feature
     *            过滤类型ID，0：全部、1：原创、2：图片、3：视频、4：音乐，默认为0。
     * @param int $trim_user
     *            返回值中user字段开关，0：返回完整user字段、1：user字段仅返回user_id，默认为0。
     *            
     * @return array
     */
    public function statuses__friends_timeline( $since_id = 0 , $max_id = 0 , $count = 20 , $page = 1 , $base_app = 0 , $feature = 0 , $trim_user = 0 )
    {
        $get = array( 
                'since_id' => $since_id , 
                'max_id' => $max_id , 
                'count' => $count , 
                'page' => $page , 
                'base_app' => $base_app , 
                'feature' => $feature , 
                'trim_user' => $trim_user 
        );
        return $this -> HTTPGet( 'statuses/friends_timeline.json' , $get );
    }
}