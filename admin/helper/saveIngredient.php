<?php
include_once '../../connection.php';
if ($db_conn) {
    if (array_key_exists('saveIngredient', $_POST)) {

        $iname = $_POST['iName'];
        $ingredientDescription = $_POST['ingredientDescription'];
        $ingredientFacts = $_POST['ingredientFacts'];

        executePlainSQL("UPDATE INGREDIENT SET DESCRIPTION ='$ingredientDescription', NUTRITIONALFACTS ='$ingredientFacts' WHERE INAME='$iname'");

        OCICommit($db_conn);
    }
    OCILogoff($db_conn);
    header("location: ../manageIngredients.php");
    exit;
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
