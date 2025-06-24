<?php
namespace DoverunnerProxy\Service;

use Doverunner\DoverunnerDrmTokenClient;
use Doverunner\PlaybackPolicyRequest;
use Doverunner\TokenBuilder;
use DoverunnerProxy\Common\DrmType;
use DoverunnerProxy\Common\Util;
use DoverunnerProxy\Exception\DoverunnerProxyException;


/**
 * Class ProxyService
 * @package DoverunnerProxy\Service
 *
 */
class ProxyService{

    const RESPONSE_FORMAT_ORIGINAL = "ORIGINAL";
    const RESPONSE_FORMAT_JSON = "JSON";
    const RESPONSE_FORMAT_CUSTOM = "CUSTOM";

    private $_config;


    public function __construct() {
        $this->_config = include __DIR__ . '/../Config/Config.php';
    }


    public function getLicenseData($_mode, $_doverunnerClientMeta, $_requestBody, $_drmType){
        $_responseData = null;

        // Token creation
        $_doverunnerCustomData = $this->createDoverunnerCustomData($_drmType);

        // license server
        $_licenseResponse = $this->callLicenseServer($_mode, $this->_config['license_url'], $_requestBody, $_doverunnerCustomData, $_drmType, $_doverunnerClientMeta);

        // response data
        $_responseData = $this->checkResponseData($_licenseResponse, $_drmType);

        return $_responseData;
    }

    /**
     * @throws DoverunnerProxyException
     */
    public function getClearKeyLicense($_drmType) {

        $licenseServerUrl = $this->_config['clearKey_license_url'] ?? null;
        $siteId = $this->_config['siteId'] ?? null;
        $cid = "palmulti"; // 필요시 변경 가능

        if (!$licenseServerUrl || !$siteId) {
            throw new DoverunnerProxyException(9002);
        }

        // URL 생성
        $url = sprintf("%s?siteId=%s&cid=%s", $licenseServerUrl, $siteId, $cid);

        // callLicenseServer를 GET 방식으로 호출
        return $this->callLicenseServer(
            null,
            $url,
            null,
            null,
            $_drmType,
            null
        );
    }

    /**
     * Token creation
     *
     * @param $_drmType
     * @return string
     * @throws \Doverunner\Exception\DoverunnerTokenException
     */
    private function createDoverunnerCustomData($_drmType){
        $_siteKey = $this->_config['siteKey'];
        $_accessKey = $this->_config['accessKey'];
        $_siteId = $this->_config['siteId'];


        $util = new Util();
        $_tokenResponseFormat = $util->nvl($this->_config['token_res_format'], self::RESPONSE_FORMAT_ORIGINAL);


        // Token client
        $_DoverunnerDrmTokenClient = new DoverunnerDrmTokenClient();
        $pallyConTokenClient =  $_DoverunnerDrmTokenClient
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
        }else if ( strtoupper($_drmType) == $drmType::WISEPLAY ) {
            $pallyConTokenClient->wiseplay();
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
        // https://doverunner.com/docs/en/multidrm/license/license-token/#license-policy-json
        // this sample rule : limit 3600 seconds license.
        $playbackPolicyRequest = new PlaybackPolicyRequest(true, 600);


        //TODO 3.
        // create DoverunnerDrmTokenPolicy
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
     * @param $_doverunnerCustomData
     * @param $_drmType
     * @return string
     * @throws DoverunnerProxyException
     */
    private function callLicenseServer($_mode, $_url, $_requestBody, $_doverunnerCustomData, $_drmType, $_doverunnerClientMeta){

        $_handle = curl_init();
        $_headerData = array();
        try{

            $_modeParam = "";
            if ($_mode != null && $_mode == "getserverinfo" ){
                $_modeParam = "?mode=getserverinfo";
                $_doverunnerCustomData = null;
            }

            curl_setopt($_handle, CURLOPT_URL, $_url."".$_modeParam);
            curl_setopt($_handle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($_handle, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($_handle, CURLOPT_AUTOREFERER, true);
            curl_setopt($_handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($_handle, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($_handle, CURLOPT_CONNECTTIMEOUT, 5);

            $drmType = new \DoverunnerProxy\Common\DrmType();
            if ($_mode != null && $_mode == "getserverinfo" ||  strtoupper($_drmType) == $drmType::CLEARKEY){
                curl_setopt($_handle, CURLOPT_POST, false);
            }else {
                curl_setopt($_handle, CURLOPT_POST, true);
            }


            $drmType = new DrmType();
            if ( strtoupper($_drmType) == $drmType::FAIRPLAY ){
                array_push($_headerData, "Content-Type: application/x-www-form-urlencoded");

            }else if ( strtoupper($_drmType) == $drmType::NCG ) {
                array_push($_headerData, "Content-Type: application/x-www-form-urlencoded");
            }else if ( strtoupper($_drmType) == $drmType::WISEPLAY ) {
                array_push($_headerData, "Content-Type: application/json");
            }else{
                array_push($_headerData, "Content-Type: application/octet-stream");
            }

            if ( $_doverunnerCustomData != null && $_doverunnerCustomData != "" ) {
                array_push($_headerData, "doverunner-customdata-v2: " . $_doverunnerCustomData);
            }

            if ( $_doverunnerClientMeta != null && $_doverunnerClientMeta != "" ) {
                array_push($_headerData, "doverunner-client-meta: " . $_doverunnerClientMeta);
            }

            curl_setopt($_handle, CURLOPT_HTTPHEADER, $_headerData);

            if ( $_requestBody != null &&  $_requestBody != "") {
                curl_setopt($_handle, CURLOPT_POSTFIELDS, $_requestBody);
            }

            $_responseData = curl_exec($_handle);

            return $_responseData;

        } catch (Exception $e){
            throw new DoverunnerProxyException(9001);
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

        }else{
            $_responseData = $_licenseResponse;
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