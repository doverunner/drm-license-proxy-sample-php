<?php
namespace DoverunnerProxy;

include_once("Common/AutoLoad.php");
include_once("Common/CorsConfig.php");

use DoverunnerProxy\Service\ProxyService;

$proxyService = new ProxyService();

// get Header
$_headers = apache_request_headers();
$_pallyconClientMeta = @$_headers["pallycon-client-meta"];

// get Parameter
$_requestBody = file_get_contents("php://input");   // get raw data
$_drmType = $_REQUEST["drmType"];
$_mode = @$_REQUEST["mode"];

$drmType = new \DoverunnerProxy\Common\DrmType();
if (strtoupper($_drmType) == $drmType::CLEARKEY) {
    $_responseData = $proxyService->getClearKeyLicense($_drmType);
} else {
    $_responseData = $proxyService->getLicenseData($_mode, $_pallyconClientMeta, $_requestBody, $_drmType);
}
// get License data


print_r($_responseData);


