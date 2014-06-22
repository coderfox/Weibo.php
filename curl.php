<?php
/**
 * cURL functions
 *
 * @author 小傅Fox[<xfox@cotr.me>]
 */
/**
 * Post
 *
 * @param unknown $url            
 * @param unknown $post            
 * @param string $header            
 * @param string $header_type            
 * @throws Exception
 * @return mixed
 */
function WeiboPHP_cURL_Post($url, $post, $header = NULL, $header_type = '0'){
    // init
    $ch = curl_init( $url );
    // set post string
    $f_post = '';
    foreach ( $post as $key => $value ) {
        $f_post = $f_post . '&' . urlencode( $key ) . '=' . urlencode( $value );
    }
    $f_post = substr( $f_post, 1 );
    // set header
    if ($header != NULL) {
        $i = 0;
        $f_header = array ();
        foreach ( $header as $key => $value ) {
            $f_header [$i] = "{$key}: {$value}";
            $i ++;
        }
    }
    // setopt
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $f_post );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    if (isset( $f_header )) {
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $f_header );
    }
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
    curl_setopt( $ch, CURLOPT_HEADER, $header_type );
    // exec
    $r = curl_exec( $ch );
    if (curl_errno( $ch ) != 0) {
        throw new Exception( 'cURL error.' . curl_error( $ch ), curl_errno( $ch ) );
    }
    // return
    return $r;
}
/**
 * Get
 *
 * @param unknown $url            
 * @param unknown $get            
 * @param string $header            
 * @param string $header_type            
 * @throws Exception
 * @return mixed
 */
function WeiboPHP_cURL_Get($url, $get, $header = NULL, $header_type = '0'){
    // set post string
    $f_get = '';
    foreach ( $get as $key => $value ) {
        $f_get = $f_get . '&' . urlencode( $key ) . '=' . urlencode( $value );
    }
    $f_get = substr( $f_get, 1 );
    // set header
    if ($header != NULL) {
        $i = 0;
        foreach ( $header as $key => $value ) {
            $f_header [$i] = "{$key}: {$value}";
            $i ++;
        }
    }
    // init
    $ch = curl_init( $url . '?' . $f_get );
    // setopt
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    if (isset( $f_header )) {
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $f_header );
    }
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
    curl_setopt( $ch, CURLOPT_HEADER, $header_type );
    // exec
    $r = curl_exec( $ch );
    if (curl_errno( $ch ) != 0) {
        throw new Exception( 'cURL error.' . curl_error( $ch ), curl_errno( $ch ) );
    }
    // return
    return $r;
}
/**
 * Post File
 *
 * @param unknown $url            
 * @param unknown $post            
 * @param string $header            
 * @param string $header_type            
 * @throws Exception
 * @return mixed
 */
function WeiboPHP_cURL_PostArr($url, $post, $header = NULL, $header_type = '0'){
    // init
    $ch = curl_init( $url );
    // set header
    if ($header != NULL) {
        $i = 0;
        $f_header = array ();
        foreach ( $header as $key => $value ) {
            $f_header [$i] = "{$key}: {$value}";
            $i ++;
        }
    }
    // setopt
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $post );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    if (isset( $f_header )) {
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $f_header );
    }
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
    curl_setopt( $ch, CURLOPT_HEADER, $header_type );
    // exec
    $r = curl_exec( $ch );
    if (curl_errno( $ch ) != 0) {
        throw new Exception( 'cURL error.' . curl_error( $ch ), curl_errno( $ch ) );
    }
    // return
    return $r;
}
