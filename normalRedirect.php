<?php
if (!($_COOKIE['userEmail'] && ($_COOKIE['userType'] === "normal"))) {
    header("location: ../index.php");
    exit;
}
?>