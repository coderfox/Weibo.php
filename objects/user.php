<?php

namespace WeiboPHP;

include_once 'modules/api.php';
include_once 'objects/status.php';
use WeiboPHP\API;
use WeiboPHP\Status;
/*
 * User object
 */
class User{
    private $id;
    public function __construct($i){
        if (is_numeric( $i )) {
            $this -> id = $i;
        } elseif (is_string( $i )) {
            throw new \Exception( '$id not a number', 0 );
        }
    }
    /**
     *
     * @param Token $token            
     * @return Ambigous <\WeiboPHP\mixed, mixed>
     */
    public function GetInfo($token){
        $info = API::Query( '2/user/show.json', $token, 'get', array (
                'id' => $this -> id 
        ) );
        $info ['status'] = new Status( $info ['status'] ['id'] );
        return $info;
    }
}