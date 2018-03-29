<?php
include_once '../connection.php';

if ($db_conn) {
    if (array_key_exists('deleteButton', $_POST)) {
        foreach ($_POST["chkDel"] as $email) {
            $email = trim($email," ");
            $query = "DELETE FROM USERS WHERE EMAIL = '$email'";
            executePlainSQL($query);
            OCICommit($db_conn);
        }
    }
    OCILogoff($db_conn);
    header("location: ../admin/manageUsers.php");
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
