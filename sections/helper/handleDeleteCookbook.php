<?php
include_once '../../connection.php';

if ($db_conn) {

    $userEmail = $_COOKIE["userEmail"];
    if (array_key_exists('deleteCookbookSubmit', $_POST)) {
        $cid = $_POST["cid"];
        $deleteQuery = "DELETE FROM MANAGEDCOOKBOOK WHERE EMAIL = '" . $userEmail . "' AND CID = '" . $cid . "'";
        executePlainSQL($deleteQuery);
        OCICommit($db_conn);
    } else if (array_key_exists('deleteCookbookMinSubmit', $_POST)) {
        $minCID = $_POST["minCID"];
        $deleteQuery = "DELETE FROM MANAGEDCOOKBOOK WHERE EMAIL = '" . $userEmail . "' AND CID = '" . $minCID . "'";
        executePlainSQL($deleteQuery);
        OCICommit($db_conn);
    } else if (array_key_exists('deleteCookbookMaxSubmit', $_POST)) {
        $maxCID = $_POST["maxCID"];
        $deleteQuery = "DELETE FROM MANAGEDCOOKBOOK WHERE EMAIL = '" . $userEmail . "' AND CID = '" . $maxCID . "'";
        executePlainSQL($deleteQuery);
        OCICommit($db_conn);
    }

        OCILogoff($db_conn);
        header("Location: ../cookbooks.php");

} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}

?>