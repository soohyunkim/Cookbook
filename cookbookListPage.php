<?php
    include_once 'connection.php';

    if ($db_conn) {

        //DISPLAY THE LIST OF COOKBOOKS
        $email = "alice123@sample.com"; //TODO: Change to grab current user's email
        if ($email != null) {
            $query = "SELECT COOKBOOKTITLE, DESCRIPTION, CID FROM MANAGEDCOOKBOOK WHERE EMAIL = '" . $email . "'";
            $result=  executePlainSQL($query);

            echo "<h1>My Cookbooks</h1>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<div class='result'>";
                if (array_key_exists("COOKBOOKTITLE", $row) && array_key_exists("CID", $row)) {
                    //TODO: Hyperlink to cookbook page based on the CID
                    $cid = $row["CID"];
                    echo "<h2><a href=\"\">" . $row["COOKBOOKTITLE"] . "</a></h2>";
                    echo "<p>Description: " . $row["DESCRIPTION"] . "</p><br>";
                } else {
                    echo "<p>There are no recipes that match your search.</p>";
                }
            }
            OCILogoff($db_conn);
        }
    } else {
        echo "cannot connect";
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
    }
?>