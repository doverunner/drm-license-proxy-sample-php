<?php
namespace PallyConProxy;

include_once("Common/AutoLoad.php");
include_once("Common/CorsConfig.php");

use PallyConProxy\Common\DrmType;
use PallyConProxy\Common\Util;
use PallyConProxy\Service\ProxyService;

$util = new Util();
$drmType = new DrmType();
$proxyService = new ProxyService();


// get Header
$_headers = apache_request_headers();
$_contentType = $_headers["Content-Type"];
$_pallyconClientMeta = @$_headers["pallycon-client-meta"];

// get Parameter
$_requestBody = file_get_contents("php://input");   // get raw data
$_drmType = $_REQUEST["drmType"];
$_spc = $_POST["spc"];
$_mode = @$_REQUEST["mode"];

// FairPlay
if ( strtoupper($_drmType) == $drmType::FAIRPLAY ) {
    $_requestBody = $util->getBytes($_spc);
}

// get License data
$_responseData = $proxyService->getLicenseData($_mode, $_pallyconClientMeta, $_requestBody, $_drmType);

print_r($_responseData);


