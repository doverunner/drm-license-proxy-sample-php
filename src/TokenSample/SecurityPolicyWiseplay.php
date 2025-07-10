<?php

namespace DoveRunner;

use DoveRunner\Exception\DoveRunnerTokenException;
use function PHPUnit\Framework\isEmpty;

class SecurityPolicyWiseplay
{
    public $_securityLevel;
    public $_outputControl;

    public function __construct($securityLevel=0, $_outputControl=1)
    {
        if(is_numeric($securityLevel)){
            $this->_securityLevel = $securityLevel;
        }else{
            throw new DoveRunnerTokenException(1055);
        }

        if(!empty($_outputControl)){
            $this->_outputControl = $_outputControl;
        } else {
            throw new DoveRunnerTokenException(1056);
        }
    }

    public function toArray()
    {
        $arr = [];
        if (isset($this->_securityLevel)) {
            $arr["security_level"] = $this->_securityLevel;
        }
        if (isset($this->_outputControl)) {
            $arr["output_control"] = $this->_outputControl;
        }
        return $arr;
    }

    /**
     * @return int|string
     */
    public function getSecurityLevel()
    {
        return $this->_securityLevel;
    }

    /**
     * @param int|string $securityLevel
     */
    public function setSecurityLevel($securityLevel)
    {
        $this->_securityLevel = $securityLevel;
    }

    /**
     * @return int|string
     */
    public function getOutputControl()
    {
        return $this->_outputControl;
    }

    /**
     * @param int|string $outputControl
     */
    public function setOutputControl($outputControl)
    {
        $this->_outputControl = $outputControl;
    }
}