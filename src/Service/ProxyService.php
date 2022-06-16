<?php
namespace PallyConProxy\Service;

use PallyCon\PallyConDrmTokenClient;
use PallyCon\PlaybackPolicyRequest;
use PallyCon\TokenBuilder;
use PallyConProxy\Common\DrmType;
use PallyConProxy\Common\Util;
use PallyConProxy\Exception\PallyConProxyException;


/**
 * Class ProxyService
 * @package PallyConProxy\Service
 *
 */
class ProxyService{

    const RESPONSE_FORMAT_ORIGINAL = "ORIGINAL";
    const RESPONSE_FORMAT_JSON = "JSON";
    const RESPONSE_FORMAT_CUSTOM = "CUSTOM";

    private $_config;


    public function __construct() {
        $this->_config = include "Config/Config.php";
    }


    public function getLicenseData($_mode, $_sampleData, $_requestBody, $_drmType){
        $_responseData = null;

        // Token creation
        $_pallyconCustomData = $this->createPallyConCustomData($_sampleData, $_drmType);


        $_modeParam = "";
        if ($_mode != null && $_mode == "getserverinfo" ){
            $_modeParam = "?mode=getserverinfo";
        }

        // license server
        $_licenseResponse = $this->callLicenseServer($_mode, $this->_config['license_url'] + $_modeParam, $_requestBody, $_pallyconCustomData, $_drmType);


        // response data
        $_responseData = $this->checkResponseData($_licenseResponse, $_drmType);

        return $_responseData;
    }


    /**
     * Token creation
     *
     * @param $_sampleData
     * @param $_drmType
     * @return string
     * @throws \PallyCon\Exception\PallyConTokenException
     */
    private function createPallyConCustomData($_sampleData, $_drmType){
        $_siteKey = $this->_config['siteKey'];
        $_accessKey = $this->_config['accessKey'];
        $_siteId = $this->_config['siteId'];


        $util = new Util();
        $_tokenResponseFormat = $util->nvl($this->_config['token_res_format'], self::RESPONSE_FORMAT_ORIGINAL);


        // Token client
        $_PallyConDrmTokenClient = new PallyConDrmTokenClient();
        $pallyConTokenClient =  $_PallyConDrmTokenClient
                                    ->siteKey($_siteKey)
                                    ->accessKey($_accessKey)
                                    ->siteId($_siteId)
                                    ->responseFormat($_tokenResponseFormat);


        $drmType = new DrmType();
        if ( strtoupper($_drmType) == $drmType::FAIRPLAY ){
            $pallyConTokenClient->fairplay();
        }else if ( strtoupper($_drmType) == $drmType::WIDEVINE ) {
            $pallyConTokenClient->widevine();
        }else if ( strtoupper($_drmType) == $drmType::NCG ) {
            $pallyConTokenClient->ncg();
        }else {
            $pallyConTokenClient->playready();
        }


        //TODO 1.
        // Add sample data processing
        // ....
        // cid, userId is required
        $pallyConTokenClient
            ->cid("testCid")
            ->userId("proxySample");


        //TODO 2.
        // Create license rule
        // https://pallycon.com/docs/en/multidrm/license/license-token/#license-policy-json
        // this sample rule : limit 3600 seconds license.
        $playbackPolicyRequest = new PlaybackPolicyRequest(true, 600);


        //TODO 3.
        // create PallyConDrmTokenPolicy
        // Set the created playbackpolicy, securitypolicy, and externalkey.
        $policyRequest = (new TokenBuilder)
            ->playbackPolicy($playbackPolicyRequest)
            //->securityPolicy($securityPolicyRequest)
            ->build();


        // Token is created with the created token policy.
        $result = $pallyConTokenClient
            ->policy($policyRequest)
            ->execute();

        return $result;

    }


    /**
     * Connection to License Server
     *
     * @param $_mode
     * @param $_url
     * @param $_requestBody
     * @param $_pallyconCustomData
     * @param $_drmType
     * @return string
     * @throws PallyConProxyException
     */
    private function callLicenseServer($_mode, $_url, $_requestBody, $_pallyconCustomData, $_drmType){

        $_handle = curl_init();
        $_headerData = array();
        try{

            curl_setopt($_handle, CURLOPT_URL, $_url);
            curl_setopt($_handle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($_handle, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($_handle, CURLOPT_AUTOREFERER, true);
            curl_setopt($_handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($_handle, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($_handle, CURLOPT_CONNECTTIMEOUT, 5);

            if ($_mode != null && $_mode == "getserverinfo" ){
                curl_setopt($_handle, CURLOPT_POST, false);
            }else {
                curl_setopt($_handle, CURLOPT_POST, true);
            }



            $drmType = new DrmType();
            if ( strtoupper($_drmType) == $drmType::FAIRPLAY ){
                array_push($_headerData, "Content-Type: application/x-www-form-urlencoded");
                $_requestBody = "spc=". implode(array_map("chr", $_requestBody));

            }else if ( strtoupper($_drmType) == $drmType::NCG ){
                array_push($_headerData, "Content-Type: application/x-www-form-urlencoded");

            }else{
                array_push($_headerData, "Content-Type: application/octet-stream");
            }
            array_push($_headerData, "pallycon-customdata-v2: ".$_pallyconCustomData);

            curl_setopt($_handle, CURLOPT_HTTPHEADER, $_headerData);

            if ( $_requestBody != null ) {
                curl_setopt($_handle, CURLOPT_POSTFIELDS, $_requestBody);
            }


            $_responseData = curl_exec($_handle);

            return $_responseData;

        } catch (Exception $e){
            throw new PallyConProxyException(9001);
        } finally{
            curl_close($_handle);
        }

    }


    /**
     * response data parsing
     *
     * @param $_licenseResponse
     * @param $_drmType
     * @return mixed
     */
    private function checkResponseData($_licenseResponse, $_drmType){

        $util = new Util();
        $_tokenResponseFormat = strtoupper($util->nvl($this->_config['token_res_format'], self::RESPONSE_FORMAT_ORIGINAL));
        $_proxyResponseFormat = strtoupper($util->nvl($this->_config['proxy_response_format'], self::RESPONSE_FORMAT_ORIGINAL));

        if(self::RESPONSE_FORMAT_JSON == $_tokenResponseFormat){
            $_responseJson = json_decode($_licenseResponse);

            //TODO 4. If you want to control ResponseData, do it here.
            $_deviceInfo = $_responseJson->device_info;
            $_deviceId = null;
            if ( $_deviceInfo != null ){
                $_deviceId = $_deviceInfo->device_id;
            }


            if(self::RESPONSE_FORMAT_ORIGINAL == $_proxyResponseFormat){
                $_responseData = $this->convertResponseData($_licenseResponse, $_responseJson, $_drmType);
            }

        }

        return $_responseData;
    }


    /**
     * custom -> original  convert
     *
     * @param $_licenseResponse
     * @param $_responseJson
     * @param $_drmType
     * @return array|false|string|null
     */
    private function convertResponseData($_licenseResponse, $_responseJson, $_drmType){
        $_responseData = null;

        $util = new Util();
        $drmType = new DrmType();

        $_license = $_responseJson->license;
        if($_license != null){
            if ( strtoupper($_drmType) == $drmType::WIDEVINE ) {
                $_responseData = pack("H*", $_license);
            }else{
                $_responseData = $_license;
            }
        }else{
            $_responseData = $_licenseResponse;
        }

        return $_responseData;
    }
}