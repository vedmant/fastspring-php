<?php
error_reporting(1);
ini_set('display_errors', 1);

include "config.php";
include "../fastspring.php";
include "helpers.php";

$fastspring = new FastSpring(FS_COMPANY_ID, FS_STORE_ID, FS_EMAIL, FS_PASSWORD);
$fastspring->test_mode = true;

session_start();

if( !isset($_SESSION["user_id"])) $_SESSION["user_id"] = rand(1,999);
