<?php
    include_once '../../connection.php';

    if ($db_conn) {

        if (array_key_exists('addToCookbook', $_POST)) {

            $userEmail = $_COOKIE["userEmail"];
            $cid = $_POST["cookbookDropdown"];
            $rid = $_POST["rid"];

            // check if recipe exists in cookbook already
            $query = "SELECT RID FROM CONSISTSOF WHERE EMAIL = '$userEmail' AND CID = '$cid' AND RID = '$rid'";
            $result = executePlainSQL($query);

            // if there's no rows, the recipe doesn't exist in the cookbook
            $row = OCI_Fetch_Array($result, OCI_BOTH);
            if (!$row) {
                $queryAdd = "INSERT INTO ConsistsOf(email, cid, rid) VALUES ('$userEmail', '$cid', '$rid')";
                executePlainSQL($queryAdd);
            }
            OCICommit($db_conn);
        }
        OCILogoff($db_conn);

        // redirect user to cookbook where recipe was added
        if (!empty($cid)) {
            header("Location: ../cookbookrecipespage.php?cid=$cid");
        } else if (!empty($rid)) {
            header("Location: ../recipe.php?rid=$rid");
        } else {
            header("Location: ../cookbooks.php");
        }

    } else {
        echo "cannot connect";
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
    }

?>