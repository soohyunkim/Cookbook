<?php
include_once '../../connection.php';
if ($db_conn) {
    if (array_key_exists('addUser', $_POST)) {

        $userEmail = $_POST['userEmail'];
        $userPassword = $_POST['userPassword'];
        $userType = $_POST['userType'];

        executePlainSQL("INSERT INTO Users (email, password, type) VALUES ('$userEmail', '$userPassword', '$userType')");

        OCICommit($db_conn);
    }
    OCILogoff($db_conn);
    header("location: ../manageUsers.php");
    exit;
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
