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
     * 构造函数0:高权应用
     *
     * @ param string $username
     * 用户名
     * @ param string $password
     * 密码
     * @ param int $app_key
     * App Key
     * @ param string $r_uri
     * redirect_uri
     *
     * @ throws WeiboEx
     *
     * 构造函数1:低权应用
     *
     * @ param string $username
     * 用户名
     * @ param string $password
     * 密码
     * @ param int $app_key
     * App Key
     * @ param string $app_secret
     * App Secret
     * @ param string $r_uri
     * redirect_uri
     *
     * @ throws WeiboEx
     */
    public function __construct()
    {
        $args = func_get_args();
        if( count( $args ) == 4 ){
            $this -> __construct0( $args[ 0 ] , $args[ 1 ] , $args[ 2 ] , $args[ 3 ] );
        } elseif( count( $args ) == 5 ){
            $this -> __construct1( $args[ 0 ] , $args[ 1 ] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] );
        } else{
            throw new Exception( 'Construct args error!' );
        }
    }
    /**
     * 构造函数0:高权应用
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
    private function __construct0( $username , $password , $app_key , $r_uri )
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
                'passwd' => $password , 
                'scope' => 'all' 
        );
        $header = array( 
                'Referer' => 'https://api.weibo.com/oauth2/authorize?client_id=' . $app_key . '&redirect_uri=' . $r_uri . '&scope=all&response_type=token' 
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
    /**
     * 构造函数1:低权应用
     *
     * @param string $username
     *            用户名
     * @param string $password
     *            密码
     * @param int $app_key
     *            App Key
     * @param string $app_secret
     *            App Secret
     * @param string $r_uri
     *            redirect_uri
     *            
     * @throws WeiboEx
     */
    private function __construct1( $username , $password , $app_key , $app_secret , $r_uri )
    {
        $post_arr = array( 
                'client_id' => $app_key , 
                'redirect_uri' => $r_uri , 
                'action' => 'submit' , 
                'response_type' => 'code' , 
                'isLoginSina' => '0' , 
                'from' => '' , 
                'regCallback' => '' , 
                'state' => '' , 
                'ticket' => '' , 
                'withOfficalFlag' => '0' , 
                'userId' => $username , 
                'passwd' => $password , 
                'scope' => 'all' 
        );
        $header = array( 
                'Referer' => 'https://api.weibo.com/oauth2/authorize?client_id=' . $app_key . '&redirect_uri=' . $r_uri . '&scope=all&response_type=code' 
        );
        $r = WeiboPHP_cURL_Post( 'https://api.weibo.com/oauth2/authorize' , $post_arr , $header , 2 );
        if( ! strpos( $r , 'HTTP/1.1 200 OK' ) ){
            $p_r_uri = parse_url( $r_uri );
            if( ! isset( $p_r_uri[ 'query' ] ) ){
                $r_uri = str_replace( '/' , '\/' , $r_uri );
                preg_match( '/Location: ' . $r_uri . '\?code=[^\s]*/' , $r , $match );
                $r_uri = str_replace( '\/' , '/' , $r_uri );
                $rtc = str_replace( 'Location: ' . $r_uri . '?code=' , '' , $match[ 0 ] );
                $post = array( 
                        'client_id' => $app_key , 
                        'client_secret' => $app_secret , 
                        'grant_type' => 'authorization_code' , 
                        'code' => $rtc , 
                        'redirect_uri' => $r_uri 
                );
                $rt = json_decode( WeiboPHP_cURL_Post( 'https://api.weibo.com/oauth2/access_token' , $post ) , true );
                $this -> token = $rt[ 'access_token' ];
            } else{
                $r_uri = str_replace( '/' , '\/' , $r_uri );
                preg_match( '/Location: ' . $r_uri . '&code=[^\s]*/' , $r , $match );
                $r_uri = str_replace( '\/' , '/' , $r_uri );
                $rtc = str_replace( 'Location: ' . $r_uri . '&code=' , '' , $match[ 0 ] );
                $post = array( 
                        'client_id' => $app_key , 
                        'client_secret' => $app_secret , 
                        'grant_type' => 'authorization_code' , 
                        'code' => $rtc , 
                        'redirect_uri' => $r_uri 
                );
                $rt = json_decode( WeiboPHP_cURL_Post( 'https://api.weibo.com/oauth2/access_token' , $post ) , true );
                $this -> token = $rt[ 'access_token' ];
            }
        } else{
            throw new WeiboEx( 'Authorize error.' . $r );
        }
    }
    /**
     * 以POST方法进行接口请求
     *
     * @param string $url
     *            api.weibo.com下的相对路径
     * @param string $post
     *            POST数组
     * @param array $header
     *            HEADER数组
     *            
     * @throws Exception
     * @return string
     */
    public function HTTPPost( $url , $post , $header = NULL )
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
    /**
     * 以GET方法进行接口请求
     *
     * @param string $url
     *            api.weibo.com下的相对路径
     * @param string $get
     *            GET数组
     * @param array $header
     *            HEADER数组
     *            
     * @throws Exception
     * @return string
     */
    public function HTTPGet( $url , $get , $header = NULL )
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
     * 以POST方法进行接口请求，提交一个文件
     *
     * @param string $url
     *            api.weibo.com下的相对路径
     * @param string $post
     *            POST数组
     * @param array $header
     *            HEADER数组
     *            
     * @throws Exception
     * @return string
     */
    public function HTTPPostArr( $url , $post , $header = NULL )
    {
        $url = $this -> host . $url;
        $post[ 'access_token' ] = $this -> token;
        $r = json_decode( WeiboPHP_cURL_PostArr( $url , $post , $header , 0 ) , true );
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
    /**
     * 获取当前登录用户及其所关注用户的最新微博
     * Wiki:{@link http://open.weibo.com/wiki/2/statuses/home_timeline}
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
    public function statuses__home_timeline( $since_id = 0 , $max_id = 0 , $count = 20 , $page = 1 , $base_app = 0 , $feature = 0 , $trim_user = 0 )
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
        return $this -> HTTPGet( 'statuses/home_timeline.json' , $get );
    }
    /**
     * 获取当前登录用户及其所关注用户的最新微博的ID
     * Wiki:{@link http://open.weibo.com/wiki/2/statuses/friends_timeline/ids}
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
     *            
     * @return array
     */
    public function statuses__friends_timeline__ids( $since_id = 0 , $max_id = 0 , $count = 20 , $page = 1 , $base_app = 0 , $feature = 0 )
    {
        $get = array( 
                'since_id' => $since_id , 
                'max_id' => $max_id , 
                'count' => $count , 
                'page' => $page , 
                'base_app' => $base_app , 
                'feature' => $feature 
        );
        return $this -> HTTPGet( 'statuses/friends_timeline/ids' , $get );
    }
    /**
     * 获取某个用户最新发表的微博列表
     * Wiki:{@link http://open.weibo.com/wiki/2/statuses/user_timeline}
     * 参数uid与screen_name二者必选其一，且只能选其一；
     * 接口升级后：uid与screen_name只能为当前授权用户，第三方微博类客户端不受影响；
     * 读取当前授权用户所有关注人最新微博列表，请使用：获取当前授权用户及其所关注用户的最新微博接口（statuses/home_timeline）；
     * 此接口最多只返回最新的2000条数据；
     *
     * @param string $uid
     *            需要查询的用户ID。
     * @param string $screen_name
     *            需要查询的用户昵称。
     * @param number $since_id
     *            若指定此参数，则返回ID比since_id大的微博（即比since_id时间晚的微博），默认为0。
     * @param number $max_id
     *            若指定此参数，则返回ID小于或等于max_id的微博，默认为0。
     * @param number $count
     *            单页返回的记录条数，最大不超过100，超过100以100处理，默认为20。
     * @param number $page
     *            返回结果的页码，默认为1。
     * @param number $base_app
     *            是否只获取当前应用的数据。0为否（所有数据），1为是（仅当前应用），默认为0。
     * @param number $feature
     *            过滤类型ID，0：全部、1：原创、2：图片、3：视频、4：音乐，默认为0。
     * @param number $trim_user
     *            返回值中user字段开关，0：返回完整user字段、1：user字段仅返回user_id，默认为0。
     *            
     * @return array
     */
    public function statuses__user_timeline( $uid = '' , $screen_name = '' , $since_id = 0 , $max_id = 0 , $count = 20 , $page = 1 , $base_app = 0 , $feature = 0 , $trim_user = 0 )
    {
        $get = array( 
                // 'uid'=>$uid,
                // 'screen_name'=>$screen_name,
                'since_id' => $since_id , 
                'max_id' => $max_id , 
                'count' => $count , 
                'page' => $page , 
                'base_app' => $base_app , 
                'feature' => $feature , 
                'trim_user' => $trim_user 
        );
        if( $uid == '' ){
            $get[ 'screen_name' ] = $screen_name;
        } elseif( $screen_name == '' ){
            $get[ 'uid' ] = $uid;
        }
        return $this -> HTTPGet( 'statuses/user_timeline' , $get );
    }
}