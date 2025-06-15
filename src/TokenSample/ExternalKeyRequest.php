<?php
namespace Doverunner;

use Doverunner\Exception\DoverunnerTokenException;

class ExternalKeyRequest
{
    public $_mpegCenc =[];
    public $_hlsAes = [];
    public $_ncg;

    /**
     * ExternalKeyRequest constructor.
     * @param null $mpegCencRequest
     * @param null $hlsAesRequest
     * @param null $ncgRequest
     */
    public function __construct($mpegCencRequest=null
                                , $hlsAesRequest=null
                                , $ncgRequest=null)
    {
        if(!empty($mpegCencRequest)){
            $this->_mpegCenc = $mpegCencRequest;
        }

        if(!empty($hlsAesRequest)){
            $this->_hlsAes = $hlsAesRequest;
        }

        if(!empty($ncgRequest)){
            $this->_ncg = $ncgRequest;
        }
    }

    public function toArray(){
        $arr= [];
        $mpegCencArr = [];
        $hlsAesArr = [];

        if(isset($this->_mpegCenc)){
            foreach ($this->_mpegCenc as $mpegCenc) {
                array_push($mpegCencArr, $mpegCenc->toArray());
            }
            $arr["mpeg_cenc"] = $mpegCencArr;
        }
        if(isset($this->_hlsAes)){
            foreach ($this->_hlsAes as $hlsAes) {
                array_push($hlsAesArr, $hlsAes->toArray());
            }
            $arr["hls_aes"] = $hlsAesArr;
        }

        if(isset($this->_ncg)){
            $arr["ncg"] = $this->_ncg->toArray();
        }

        return $arr;
    }

    /**
     * @return array|\Doverunner\MpegCencRequest|null
     */
    public function getMpegCenc()
    {
        return $this->_mpegCenc;
    }

    /**
     * @param array|\Doverunner\MpegCencRequest|null $mpegCenc
     */
    public function setMpegCenc($mpegCenc)
    {
        if(is_array($mpegCenc)){
            $this->_mpegCenc = $mpegCenc;
        }else{
            throw new DoverunnerTokenException(1019);
        }

    }

    public function pushMpegCenc(MpegCencRequest $mpegCenc){
        array_push($this->_mpegCenc, $mpegCenc);
    }

    /**
     * @return array|\Doverunner\HlsAesRequest|null
     */
    public function getHlsAes()
    {
        return $this->_hlsAes;
    }

    /**
     * @param array|\Doverunner\HlsAesRequest|null $hlsAes
     */
    public function setHlsAes($hlsAes)
    {
        if(is_array($hlsAes)){
            $this->_hlsAes = $hlsAes;
        }else{
            throw new DoverunnerTokenException(1020);
        }
    }

    public function pushHlsAes(HlsAesRequest $hlsAes){
        array_push($this->_HlsAes, $hlsAes);
    }

    /**
     * @return array|\Doverunner\NcgRequest|null
     */
    public function getNcg()
    {
        return $this->_ncg;
    }

    /**
     * @param array|\Doverunner\NcgRequest|null $ncg
     */
    public function setNcg(NcgRequest $ncg)
    {
        $this->_ncg = $ncg;
    }

}