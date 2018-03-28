<?php
include_once 'connection.php';
if ($_COOKIE['userEmail'] && $_COOKIE['userType'] == 'normal') {
    header("location: sections/search.php");
    exit;
} else if ($_COOKIE['userEmail'] && $_COOKIE['userType'] == 'admin') {
    header("location: admin/adminHeader.php");
    exit;
}
require "sections/login.php";

?>