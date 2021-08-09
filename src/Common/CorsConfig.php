<?php

// response header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Connection: close");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header('Content-Type: text/plain');


error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', 1);