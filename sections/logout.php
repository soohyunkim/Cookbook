<?php
setcookie("userEmail", '',time() - 3600, '/');
setcookie("userType", '',time() - 3600, '/');
header("location: ../index.php");
