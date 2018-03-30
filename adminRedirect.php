<?php
if (!($_COOKIE['userEmail'] && ($_COOKIE['userType'] === "admin"))) {
    header("location: ../index.php");
    exit;
}
?>