<?php
date_default_timezone_set("Asia/Kolkata");
session_start();
define("BASE_URL", "http://nec-local.com/");
define("CORE_PATH", __DIR__);
define("ROOT_PATH", substr(CORE_PATH, 0, -5) . "/");
define("INCLUDE_PATH", ROOT_PATH . "includes/");
define("HEAD_PATH", INCLUDE_PATH . "head.php");
define("HEADER_PATH", INCLUDE_PATH . "header.php");
define("FOOTER_PATH", INCLUDE_PATH . "footer.php");
define("FOOT_PATH", INCLUDE_PATH . "foot.php");
define("PROFILE_PATH", "uploads/profile/");
define("REGEX_EMAIL",  "/^[a-zA-Z0-9]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/");
define("REGEX_PHONE",  "/^(0|\+91)?[6-9][0-9]{9}$/");
define("REGEX_AGE",  "/^[1-9]([0-9]{1,2})?$/");

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "My5ql.3306");
define("DB_NAME", "nec_test");
define("PASSWORD_SALT", "%#&$+0238^&*");

require_once CORE_PATH . "/database.php";
require_once CORE_PATH . "/helper.php";