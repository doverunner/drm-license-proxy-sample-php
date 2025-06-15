<?php


namespace Doverunner;


class SecurityPolicyRequest
{
    public $_trackType;
    public $_widevine;
    public $_playready;
    public $_fairplay;
    public $_ncg;
    public $_wiseplay;

    public function __construct($trackType="ALL"
                        , SecurityPolicyWidevine $securityPolicyWidevine=null
                        , SecurityPolicyPlayReady $securityPolicyPlayReady=null
                        , SecurityPolicyFairPlay $securityPolicyFairPlay=null
                        , SecurityPolicyNcg $securityPolicyNcg=null
                        , SecurityPolicyWiseplay $securityPolicyWiseplay=null)
    {
        $this->_trackType = $trackType;
        if(!empty($securityPolicyWidevine)){
            $this->_widevine = $securityPolicyWidevine;
        }
        if(!empty($securityPolicyPlayReady)){
            $this->_playready = $securityPolicyPlayReady;
        }
        if(!empty($securityPolicyFairPlay)){
            $this->_fairplay = $securityPolicyFairPlay;
        }
        if(!empty($securityPolicyNcg)){
            $this->_ncg = $securityPolicyNcg;
        }
        if(!empty($securityPolicyWiseplay)){
            $this->_wiseplay = $securityPolicyWiseplay;
        }
    }

    public function toArray(){
        $arr= [];
        if(isset($this->_trackType)){
            $arr["track_type"] = $this->_trackType;
        }
        if(isset($this->_widevine)){
            $arr["widevine"] = $this->_widevine->toArray();
        }
        if(isset($this->_playready)){
            $arr["playready"] = $this->_playready->toArray();
        }
        if(isset($this->_fairplay)){
            $arr["fairplay"] = $this->_fairplay->toArray();
        }
        if(isset($this->_ncg)){
            $arr["ncg"] = $this->_ncg->toArray();
        }
        if(isset($this->_wiseplay)){
            $arr["wiseplay"] = $this->_wiseplay->toArray();
        }

        return $arr;
    }

    /**
     * @return string
     */
    public function getTrackType()
    {
        return $this->_trackType;
    }

    /**
     * @param string $trackType
     */
    public function setTrackType($trackType)
    {
        $this->_trackType = $trackType;
    }

    /**
     * @return SecurityPolicyWidevine
     */
    public function getWidevine()
    {
        return $this->_widevine;
    }

    /**
     * @param SecurityPolicyWidevine $widevine
     */
    public function setWidevine($widevine)
    {
        $this->_widevine = $widevine;
    }

    /**
     * @return SecurityPolicyPlayReady
     */
    public function getPlayready()
    {
        return $this->_playready;
    }

    /**
     * @param SecurityPolicyPlayReady $playready
     */
    public function setPlayready($playready)
    {
        $this->_playready = $playready;
    }

    /**
     * @return SecurityPolicyFairPlay
     */
    public function getFairplay()
    {
        return $this->_fairplay;
    }

    /**
     * @param SecurityPolicyFairPlay $fairplay
     */
    public function setFairplay($fairplay)
    {
        $this->_fairplay = $fairplay;
    }

    /**
     * @return SecurityPolicyNcg
     */
    public function getNcg()
    {
        return $this->_ncg;
    }

    /**
     * @param SecurityPolicyNcg $ncg
     */
    public function setNcg($ncg)
    {
        $this->_ncg = $ncg;
    }

    /**
     * @return SecurityPolicyWiseplay
     */
    public function getWiseplay()
    {
        return $this->_wiseplay;
    }

    /**
     * @param SecurityPolicyWiseplay $wiseplay
     */
    public function setWiseplay($wiseplay)
    {
        $this->_wiseplay = $wiseplay;
    }
}
