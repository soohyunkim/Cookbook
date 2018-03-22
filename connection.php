<html>
<?php

$config = include('configurations.php');
if ($c=OCILogon($config["db_username"], $config["db_password"], "dbhost.ugrad.cs.ubc.ca:1522/ug")) {
  echo "Successfully connected to Oracle.\n";
  OCILogoff($c);
} else {
  $err = OCIError();
  echo "Oracle Connect Error " . $err['message'];
}

?>
</html>