<?php
//api url filter
if (strpos($_SERVER['REQUEST_URI'], "global.php")) {
    require_once 'utils.php';
    PlainDie();
}

$URL = "https://visvesmruti.tech";
$Maintenance = false;
$AdminAdding = false;
$AdminApiLogin = true;
$AdminApiAccess = true;
$QR_Postfix = "_QR_VS2K19.png";
$Cert_Postfix = "_Certificate_VS2K19.pdf";