<?php require "header.php";
require "../normalRedirect.php";

$cid = $_GET["cid"];

if (empty($cid)) {
    echo "<p>No cookbook selected.<br>Click one of the menu options on the left.</p>";
} else {
    include_once '../connection.php';

    if ($db_conn) {

        $userEmail = $_COOKIE["userEmail"];

        if ($userEmail != null) {
            $cookbookInfoQuery = "SELECT COOKBOOKTITLE, DESCRIPTION
                              FROM MANAGEDCOOKBOOK
                              WHERE CID = '" . $cid . "' AND EMAIL = '" . $userEmail . "'";
            $cookbookRecipeQuery = "SELECT DISTINCT RECIPETITLE, CUISINE, DIFFICULTY, COOKINGTIME, RECIPE.RID
                                FROM RECIPE, CONSISTSOF
                                WHERE RECIPE.RID = CONSISTSOF.RID
                                AND CONSISTSOF.CID = '" . $cid . "' AND CONSISTSOF.EMAIL = '" . $userEmail . "'";
            $cookbookInfoResult = executePlainSQL($cookbookInfoQuery);
            $cookbookRecipeResult = executePlainSQL($cookbookRecipeQuery);
            //DISPLAY COOKBOOK INFORMATION
            while ($row = OCI_Fetch_Array($cookbookInfoResult, OCI_BOTH)) {
                echo "<div class='result'>";
                if (array_key_exists("COOKBOOKTITLE", $row) && array_key_exists("DESCRIPTION", $row)) {
                    echo "<h2>" . trim($row["COOKBOOKTITLE"], " ") . "</h2>";
                    echo "<p>Description: " . trim($row["DESCRIPTION"], " ") . "</p>";
                } else {
                    echo "<p>There are no cookbooks that match your search.</p>";
                }
                echo "</div>";
            }
            //DISPLAY TITLES
            while ($row = OCI_Fetch_Array($cookbookRecipeResult, OCI_BOTH)) {
                echo "<div class='result'>";
                if (array_key_exists("RID", $row)) {
                    $rid = trim($row["RID"], " ");
//                    echo "<p><a href='recipe.php?rid=" . $rid . "'>" . $row["RECIPETITLE"] . "</a>";
                    echo "<form id='delete-recipe-from-cookbook-form' class='remove-cb-recipe-form' method='post' action='helper/handleDeleteCookbookRecipe.php'>
                            <div>
                                <a href='recipe.php?rid=" . $rid . "'>" . trim($row["RECIPETITLE"], " ") . "</a> 
                                <button type='submit' class='btn remove-cb-recipe-button' name='deleteRecipeSubmit'>x</button>
                            </div>
                            <input type='hidden' name='rid' value='$rid'>
                            <input type='hidden' name='cid' value='$cid'></form>";
                } else {
                    echo "<p>There are no recipes that match your search.</p>";
                }
                echo "</div>";
            }

            OCILogoff($db_conn);
        }
    } else {
        echo "cannot connect";
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
    }
}

?>

<?php require "footer.php";?>