<?php

namespace WeiboPHP;

class WeiboEx extends \Exception{
    public $APIReturn = NULL;
    public static function Construct($message, $code, $return){
        $e = new WeiboEx( $message, $code );
        $e -> APIReturn = $return;
        return $e;
    }
}