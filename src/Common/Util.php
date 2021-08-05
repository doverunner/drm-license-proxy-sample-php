<?php
namespace PallyConProxy\Common;

/**
 * Class Util
 * @package PallyConProxy\Common
 */
class Util{

    /**
     * String -> Byte
     *
     * @param $str
     * @return array|null
     */
    public function getBytes($str){
        if ( $str != null && strlen($str) > 0 ) {
            $_requestBody = array();
            for ($i = 0; $i < strlen($str); $i++) {
                $_requestBody[] = ord($str[$i]);
            }
            return $_requestBody;
        }else{
            return null;
        }
    }


    /**
     * null check
     *
     * @param $str
     * @param null $default
     * @return string|null
     */
    public function nvl($str, $default = null){
        if (isset($str)){
            return $str;
        }else{
            return $default;
        }
    }



}