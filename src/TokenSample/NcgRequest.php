<?php
namespace Doverunner;

use Doverunner\Exception\DoverunnerTokenException;

class NcgRequest
{
    public $_cek;

    function __construct($cek)
    {
        if(preg_match('/[[:xdigit:]]{64}/', $cek)){
            $this->_cek=$cek;
        }else{
            throw new DoverunnerTokenException(1047);
        }
    }

    public function toArray(){
        $arr= [];
        if(isset($this->_cek)){
            $arr["cek"] = $this->_cek;
        }

        return $arr;
    }

    /**
     * @return mixed
     */
    public function getCek()
    {
        return $this->_cek;
    }

    /**
     * @param mixed $cek
     */
    public function setCek($cek)
    {
        $this->_cek = $cek;
    }

}