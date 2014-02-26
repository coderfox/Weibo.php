<?php
/**
 * cURL functions
 *
 * @author 小傅Fox[<xfox@cotr.me>]
 */
function WeiboPHP_cURL_Post( $url , $post , $header , $header_type = '0' )
{
    // init
    $ch = curl_init( $url );
    // set post string
    foreach( $post as $key => $value ){
        $f_post = $f_post . '&' . urlencode( $key ) . '=' . urlencode( $value );
    }
    $f_post = substr( $f_post , 1 );
    // set header
    $i = 0;
    foreach( $header as $key => $value ){
        $f_header[ $i ] = "{$key}: {$value}";
        $i ++;
    }
    $f_header[ $i ] = 'Content-Type: application/x-www-form-urlencoded';
    // setopt
    curl_setopt( $ch , CURLOPT_POST , 1 );
    curl_setopt( $ch , CURLOPT_POSTFIELDS , $post );
    curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , FALSE );
    curl_setopt( $ch , CURLOPT_SSL_VERIFYHOST , FALSE );
    curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
    curl_setopt( $ch , CURLOPT_HTTPHEADER , $header );
    curl_setopt( $ch , CURLOPT_FOLLOWLOCATION , 0 );
    curl_setopt( $ch , CURLOPT_HEADER , $header_type );
    // exec
    $r = curl_exec( $ch );
    if( curl_errno( $ch ) != 0 ){
        throw new Exception( 'cURL error.' . curl_error( $ch ) , curl_errno( $ch ) );
    }
    // return
    return $r;
}
function WeiboPHP_cURL_Get( $url , $get , $header , $header_type = '0' )
{
    // init
    $ch = curl_init( $url );
    // set post string
    foreach( $get as $key => $value ){
        $f_get = $f_get . '&' . urlencode( $key ) . '=' . urlencode( $value );
    }
    $f_get = substr( $f_get , 1 );
    // set header
    $i = 0;
    foreach( $header as $key => $value ){
        $f_header[ $i ] = "{$key}: {$value}";
        $i ++;
    }
    // setopt
    curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , FALSE );
    curl_setopt( $ch , CURLOPT_SSL_VERIFYHOST , FALSE );
    curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
    curl_setopt( $ch , CURLOPT_HTTPHEADER , $header );
    curl_setopt( $ch , CURLOPT_FOLLOWLOCATION , 0 );
    curl_setopt( $ch , CURLOPT_HEADER , $header_type );
    // exec
    $r = curl_exec( $ch );
    if( curl_errno( $ch ) != 0 ){
        throw new Exception( 'cURL error.' . curl_error( $ch ) , curl_errno( $ch ) );
    }
    // return
    return $r;
}