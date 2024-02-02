<?php
require_once "init.php";
require_once CORE_PATH . "/ajax.php";
$types = [
    "self-register"     =>  "register",
    "login"             =>  "loginCheck",
    "profile-update"    =>  "updateProfile",
    "logout"            =>  "logoutSession"
];

$function  = $types[$_POST["type"] ?? ""] ?? NULL;

if(!empty($function)) {
    $mainObj = new Ajax();
    $mainObj->$function();
} else {
    http_response_code(404);
}

