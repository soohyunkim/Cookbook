<?php
include_once '../../connection.php';

if ($db_conn) {

    if (array_key_exists('deleteRecipeSubmit', $_POST)) {

        $userEmail = $_COOKIE["userEmail"];
        $cid = $_POST["cid"];
        $rid = $_POST["rid"];

        // delete cookbook
        $deleteQuery = "DELETE FROM CONSISTSOF WHERE EMAIL = '" . $userEmail . "' AND CID = '" . $cid . "' AND RID = '" . $rid . "'";
        executePlainSQL($deleteQuery);
        OCICommit($db_conn);
    }
    OCILogoff($db_conn);
    header("Location: ../cookbookrecipespage.php?cid=$cid");

} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}

?>