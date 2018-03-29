<?php
include_once '../../connection.php';

if ($db_conn) {
    if (array_key_exists('deleteButton', $_POST)) {
        foreach ($_POST["chkDel"] as $rid) {
            $rid = trim($rid," ");
            $query = "DELETE FROM RECIPE WHERE RID = '$rid'";
            executePlainSQL($query);
            OCICommit($db_conn);
        }
    }
    OCILogoff($db_conn);
    header("location: ../manageRecipes.php");
    exit;
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
