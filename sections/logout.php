<?php
setcookie("userEmail", '',time() - 3600, '/');
header("location: ../index.php");
