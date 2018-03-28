<?php
include_once 'connection.php';

if ($db_conn) {

    //DISPLAY TITLES
    $email = "alice123@sample.com"; //TODO: Change to grab current user's email
    $cid = 1; //TODO: grab info from href to know which cookbook of that user's to display
    if ($email != null) {
        $cookbookInfoQuery = "SELECT COOKBOOKTITLE, DESCRIPTION 
                              FROM MANAGEDCOOKBOOK 
                              WHERE CID = '" . $cid . "' AND EMAIL = '" . $email . "'";
        $cookbookRecipeQuery = "SELECT DISTINCT RECIPETITLE, CUISINE, DIFFICULTY, COOKINGTIME
                                FROM RECIPE, CONSISTSOF
                                WHERE RECIPE.RID = CONSISTSOF.RID 
                                AND CONSISTSOF.CID = '" . $cid . "' AND CONSISTSOF.EMAIL = '" . $email . "'";

        $cookbookInfoResult =  executePlainSQL($cookbookInfoQuery);
        $cookbookRecipeResult = executePlainSQL($cookbookRecipeQuery);

        //DISPLAY COOKBOOK INFORMATION
        while ($row = OCI_Fetch_Array($cookbookInfoResult, OCI_BOTH)) {
            echo "<div class='result'>";
            if (array_key_exists("COOKBOOKTITLE", $row) && array_key_exists("DESCRIPTION", $row)) {
                echo "<h2>" . $row["COOKBOOKTITLE"] . "</h2>";
                echo "<p>Description: <br>" . $row["DESCRIPTION"] . "</p><br>";
            } else {
                echo "<p>There are no recipes that match your search.</p>";
            }
        }

        //DISPLAY TITLES
        while ($row = OCI_Fetch_Array($cookbookRecipeResult, OCI_BOTH)) {
            echo "<div class='result'>";
            if (array_key_exists("RECIPETITLE", $row)) {
                //TODO: Hyperlink to recipe page based on the CID
                echo "<p><a href=\"\">" . $row["RECIPETITLE"] . "</a><br>";
                echo "Cuisine: " . $row["CUISINE"] . "<br>";
                echo "Difficulty: " . $row["DIFFICULTY"] . "<br>";
                echo "Cooking Time: " . $row["COOKINGTIME"] . "</p><br>";
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