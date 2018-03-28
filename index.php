<?php
include_once 'connection.php';
require "sections/login.php";

if ($_COOKIE['loggedIn']) {
    header("location: search.php");
}
?>