<?php
include_once 'connection.php';
if ($_COOKIE['userEmail']) {
    header("location: sections/search.php");
    exit;
}
require "sections/login.php";

?>