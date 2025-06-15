<?php

namespace Doverunner;


use Doverunner\Exception\DoverunnerTokenException;

class PlaybackPolicyRequest {
    public $_limit;
    public $_persistent;
    public $_licenseDuration;
    public $_expireDate;
    public $_rentalDuration;
    public $_playbackDuration;
    public $_allowedTrackTypes;
    public $_maxStreamPerUser;
    public $_renewalDuration;

    /**
     * PlaybackPolicyRequest constructor.
     * @param bool $persistent
     * @param int $licenseDuration
     * @param string $expireDate
     * @param int $rentalDuration
     * @param int $playbackDuration
     * @param $maxStreamPerUser
     * @param $renewalDuration
     * @throws DoverunnerTokenException
     */
    public function __construct($persistent=false, $licenseDuration=0, $expireDate= "", int $rentalDuration=0, int $playbackDuration=0, $maxStreamPerUser="", $renewalDuration=0, $allowedTrackTypes="ALL")
    {
        if(!is_null($persistent)) {
            if(is_bool($persistent)){
                $this->_persistent = $persistent;
            }else{
                throw new DoverunnerTokenException(1009);
            }
        }
        if(!is_null($licenseDuration)) {
            if(is_numeric($licenseDuration)){
                $this->_licenseDuration = $licenseDuration;
            }else{
                throw new DoverunnerTokenException(1010);
            }
        }
        if(!empty($expireDate)) {
            if(preg_match('/[0-9]{4}-[0,1][0-9]-[0-5][0-9]T[0-2][0-9]:[0-5][0-9]:[0-5][0-9]Z/', $expireDate)){
                $this->_expireDate = $expireDate;
            }else{
                throw new DoverunnerTokenException(1011);
            }
        }
        if(!is_null($rentalDuration)) {
            if(is_numeric($rentalDuration)){
                $this->_rentalDuration = $rentalDuration;
            }else{
                throw new DoverunnerTokenException(1017);
            }
        }
        if(!is_null($playbackDuration)) {
            if(is_numeric($playbackDuration)){
                $this->_playbackDuration = $playbackDuration;
            }else{
                throw new DoverunnerTokenException(1016);
            }
        }
        if(!empty($allowedTrackTypes)) {
            if(is_string($allowedTrackTypes)){
                $this->_allowedTrackTypes = $allowedTrackTypes;
            }else{
                throw new DoverunnerTokenException(1013);
            }
        }
        if(!empty($maxStreamPerUser)) {
            if(is_numeric($maxStreamPerUser)){
                $this->_maxStreamPerUser = $maxStreamPerUser;
            }else{
                throw new DoverunnerTokenException(1014);
            }
        }
        if(!empty($renewalDuration)) {
            if(is_numeric($renewalDuration)){
                $this->_renewalDuration = $renewalDuration;
            }else{
                throw new DoverunnerTokenException(1015);
            }
        }
    }

    public function toArray(){
        $arr= [];
        if(isset($this->_persistent)){
            $arr["persistent"] = $this->_persistent;
        }
        if(isset($this->_allowedTrackTypes)){
            $arr["allowed_track_types"] = $this->_allowedTrackTypes;
        }
        if(isset($this->_licenseDuration)){
            $arr["license_duration"] = $this->_licenseDuration;
        }
        if(isset($this->_expireDate)){
            $arr["expire_date"] = $this->_expireDate;
        }
        if(isset($this->_rentalDuration)){
            $arr["rental_duration"] = $this->_rentalDuration;
        }
        if(isset($this->_playbackDuration)){
            $arr["playback_duration"] = $this->_playbackDuration;
        }
        if(isset($this->_maxStreamPerUser)){
            $arr["max_stream_per_user"] = $this->_maxStreamPerUser;
        }
        if(isset($this->_renewalDuration)){
            $arr["renewal_duration"] = $this->_renewalDuration;
        }
        return $arr;
    }

    /**
     * @return bool
     */
    public function isPersistent()
    {
        return $this->_persistent;
    }

    /**
     * @param bool $persistent
     */
    public function setPersistent($persistent)
    {
        $this->_persistent = $persistent;
    }

    /**
     * @return int|string
     */
    public function getLicenseDuration()
    {
        return $this->_licenseDuration;
    }

    /**
     * @param int|string $licenseDuration
     */
    public function setLicenseDuration($licenseDuration)
    {
        $this->_licenseDuration = $licenseDuration;
    }

    /**
     * @return string
     */
    public function getExpireDate()
    {
        return $this->_expireDate;
    }

    /**
     * @param string $expireDate
     */
    public function setExpireDate($expireDate)
    {
        $this->_expireDate = $expireDate;
    }

    /**
     * @return mixed
     */
    public function getAllowedTrackTypes()
    {
        return $this->_allowedTrackTypes;
    }

    /**
     * @param mixed $allowedTrackTypes
     */
    public function setAllowedTrackTypes($allowedTrackTypes)
    {
        $this->_allowedTrackTypes = $allowedTrackTypes;
    }

    /**
     * @return int|string
     */
    public function getRentalDuration()
    {
        return $this->_rentalDuration;
    }

    /**
     * @param int|string $rentalDuration
     */
    public function setRentalDuration($rentalDuration)
    {
        $this->_rentalDuration = $rentalDuration;
    }

}
?>