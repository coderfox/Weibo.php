<?php
/**
 * cURL functions
 *
 * @author 小傅Fox[<xfox@cotr.me>]
 */
function WeiboPHP_cURL_Post( $url , $post , $header = NULL , $header_type = '0' )
{
    // init
    $ch = curl_init( $url );
    // set post string
    $f_post = '';
    foreach( $post as $key => $value ){
        $f_post = $f_post . '&' . urlencode( $key ) . '=' . urlencode( $value );
    }
    $f_post = substr( $f_post , 1 );
    /* ===DEBUG=== */
    if( DEBUG ){
        echo $f_post;
    }
    // set header
    if( $header != NULL ){
        $i = 0;
        $f_header = array();
        foreach( $header as $key => $value ){
            $f_header[ $i ] = "{$key}: {$value}";
            $i ++;
        }
    }
    // $f_header[ $i ] = 'Content-Type: application/x-www-form-urlencoded';
    /* ===DEBUG=== */
    if( DEBUG ){
        var_dump( $f_header );
    }
    // setopt
    curl_setopt( $ch , CURLOPT_POST , 1 );
    curl_setopt( $ch , CURLOPT_POSTFIELDS , $f_post );
    curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , FALSE );
    curl_setopt( $ch , CURLOPT_SSL_VERIFYHOST , FALSE );
    curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
    if( isset( $f_header ) ){
        curl_setopt( $ch , CURLOPT_HTTPHEADER , $f_header );
    }
    curl_setopt( $ch , CURLOPT_FOLLOWLOCATION , 0 );
    curl_setopt( $ch , CURLOPT_HEADER , $header_type );
    /* ===DEBUG=== */
    if( DEBUG ){
        // curl_setopt($ch,CURLOPT_HTTPPROXYTUNNEL,true);
        curl_setopt( $ch , CURLOPT_PROXY , '127.0.0.1:8888' );
        // curl_setopt($ch, CURLOPT_PROXYPORT, '8080');
    }
    /* !!!DEBUG!!! */
    // exec
    $r = curl_exec( $ch );
    if( curl_errno( $ch ) != 0 ){
        throw new Exception( 'cURL error.' . curl_error( $ch ) , curl_errno( $ch ) );
    }
    // return
    return $r;
}
function WeiboPHP_cURL_Get( $url , $get , $header = NULL , $header_type = '0' )
{
    // set post string
    $f_get = '';
    foreach( $get as $key => $value ){
        $f_get = $f_get . '&' . urlencode( $key ) . '=' . urlencode( $value );
    }
    $f_get = substr( $f_get , 1 );
    // set header
    if( $header != NULL ){
        $i = 0;
        foreach( $header as $key => $value ){
            $f_header[ $i ] = "{$key}: {$value}";
            $i ++;
        }
    }
    // init
    $ch = curl_init( $url . '?' . $f_get );
    // setopt
    curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , FALSE );
    curl_setopt( $ch , CURLOPT_SSL_VERIFYHOST , FALSE );
    curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
    if( isset( $f_header ) ){
        curl_setopt( $ch , CURLOPT_HTTPHEADER , $f_header );
    }
    curl_setopt( $ch , CURLOPT_FOLLOWLOCATION , 0 );
    curl_setopt( $ch , CURLOPT_HEADER , $header_type );
    /* ===DEBUG=== */
    if( DEBUG ){
        // curl_setopt($ch,CURLOPT_HTTPPROXYTUNNEL,true);
        curl_setopt( $ch , CURLOPT_PROXY , '127.0.0.1:8888' );
        // curl_setopt($ch, CURLOPT_PROXYPORT, '8080');
    }
    /* !!!DEBUG!!! */
    // exec
    $r = curl_exec( $ch );
    if( curl_errno( $ch ) != 0 ){
        throw new Exception( 'cURL error.' . curl_error( $ch ) , curl_errno( $ch ) );
    }
    // return
    return $r;
}