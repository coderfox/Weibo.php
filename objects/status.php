<?php

namespace WeiboPHP;

include_once 'modules/api.php';
include_once 'objects/user.php';
use WeiboPHP\API;
use WeiboPHP\User;
/*
 * Status object
 */
class Status{
    private $id;
    public function __construct($id){
        if (is_numeric( $id )) {
            $this -> id = $id;
        } else {
            throw new \Exception( '$id not a number', 0 );
        }
    }
    /**
     *
     * @param Token $token            
     * @return Ambigous <\WeiboPHP\mixed, mixed>
     */
    public function GetInfo($token){
        $info = API::Query( '2/statuses/show.json', $token, 'get', array (
                'id' => $this -> id 
        ) );
        $info ['user'] = new User( $info ['user'['id']] );
        return $info;
    }
}