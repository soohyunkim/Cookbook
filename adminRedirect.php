<?php
if ($_COOKIE['userEmail'] && $_COOKIE['userType'] !== 'admin') {
    echo "You do not have permission to view this page.";
    exit;
}
if (!$_COOKIE['userEmail']) {
    header("location: ../index.php");
    exit;
}
?>