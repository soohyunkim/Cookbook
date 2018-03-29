<?php
require "header.php";
?>
<script src="../javascript/createCookbook.js"></script>

<h3 class="cookbook-section-header">Create a cookbook: </h3>
<p>Enter title and description to create your cookbook:</p>
<form method="post" action="cookbooks.php">

    <!-- Cookbook Title -->
    <div class="cookbook-create-section">
        <label>Cookbook Name:</label>
        <input type="text" id="cookbook-create-title" name="cookbookTitle">
    </div>
    <!-- Cookbook Title -->
    <div class="cookbook-create-section">
        <label>Description:</label>
        <input type="text" id="cookbook-create-description" name="cookbookDescription">
    </div>
    <!-- Submit Button -->
    <div class="cookbook-create-section">
        <button type="submit" onClick="submitCookbookForm()" name="uploadCookbook">Create Cookbook</button>
    </div>
</form>
<?php

include_once '../connection.php';

// Connect Oracle...
if ($db_conn) {

    $userEmail = $_COOKIE['userEmail'];

    if ($userEmail != null) {
        if (array_key_exists('uploadCookbook', $_POST)) {
            $cookbookInfo = array(
                ":cookbookTitle" => $_POST['cookbookTitle'],
                ":description" => $_POST['cookbookDescription'],
                ":cid" => uniqid(),
                ":email" => $userEmail,
            );
            $alltuples = array(
                $cookbookInfo,
            );
            executeBoundSQL("INSERT INTO MANAGEDCOOKBOOK VALUES (:cookbookTitle, :description, :cid, :email)", $alltuples);

            OCICommit($db_conn);
        }

        $countQuery = "SELECT DISTINCT COUNT(CID) as CIDCOUNT FROM MANAGEDCOOKBOOK WHERE EMAIL = '" . $userEmail . "'";
        $countResult = executePlainSQL($countQuery);
        $countRow = OCI_Fetch_Array($countResult, OCI_BOTH)[0];

        if ($countRow > 0) {
            echo "<h3 class='cookbook-section-header'>My cookbooks: </h3>";
            echo "<div class='container'>";
            echo "<form action='cookbooks.php' method='post'>Filter by Difficulty: ";
            echo "<label class='radio-inline'><input type='radio' name='difficulty' value='uncategorized'> Uncategorized </label>";
            echo "<label class='radio-inline'><input type='radio' name='difficulty' value='max'> Maximum </label>";
            echo "<label class='radio-inline'><input type='radio' name='difficulty' value='min'> Minimum </label>";
            echo "<input type='submit' value='submit'>";
            echo "</form>";
            echo "</div>";

        } else {
            echo "<p>You don't have any cookbooks :(. Create one!</p>";
        }

        $query = "SELECT COOKBOOKTITLE, DESCRIPTION, CID FROM MANAGEDCOOKBOOK WHERE EMAIL = '" . $userEmail . "'";
        $result = executePlainSQL($query);

        $difficultySelected = $_POST["difficulty"]; //Want to grab the difficulty sorting, and run the query accordingly
        if ($difficultySelected == 'uncategorized') {
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<div class='result'>";

                if (array_key_exists("COOKBOOKTITLE", $row) && array_key_exists("CID", $row)) {
                    $cid = $row["CID"];
                    $title = $row["COOKBOOKTITLE"];
                    $avgQuery = "select avg(r1.DIFFICULTY) as avgDifficulty
                            from MANAGEDCOOKBOOK mc1, RECIPE r1, CONSISTSOF co1
                            where mc1.CID = '" . $cid . "'
                            and co1.cid = '" . $cid . "'
                            and r1.rid = co1.rid
                            and mc1.email = '" . $userEmail . "'
                            and co1.email = '" . $userEmail . "'
                            group by mc1.cid";
                    $avgResult = executePlainSQL($avgQuery);
                    $avg = OCI_Fetch_Array($avgResult, OCI_BOTH)[0];

                    echo "<div class='container'>";
                    echo "<a href='cookbookrecipespage.php?cid=" . $cid . "'>" . $title . "</a>";
                    echo $avg;
                    echo "</div>";
                }
                echo "</div>";
            }
        } else if ($difficultySelected == 'min') {
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<div class='result'>";

                if (array_key_exists("COOKBOOKTITLE", $row) && array_key_exists("CID", $row)) {
                    $cid = $row["CID"];
                    $title = $row["COOKBOOKTITLE"];
                    $avgQuery = "select avg(r1.DIFFICULTY) as avgDifficulty
                            from MANAGEDCOOKBOOK mc1, RECIPE r1, CONSISTSOF co1
                            where mc1.CID = '" . $cid . "'
                            and co1.cid = '" . $cid . "'
                            and r1.rid = co1.rid
                            and mc1.email = '" . $userEmail . "'
                            and co1.email = '" . $userEmail . "'
                            group by mc1.cid";
                    $avgResult = executePlainSQL($avgQuery);
                    $avg = OCI_Fetch_Array($avgResult, OCI_BOTH)[0];
                    $filteredQuery = "select MIN(avgDifficulty) as minAverageDifficulty
                            from (" . $avgQuery . "), managedcookbook mc2, consistsof co2
                            where mc2.email = co2.EMAIL";
                    $filteredResult = executePlainSQL($filteredQuery);
                    $filteredValue = OCI_Fetch_Array($filteredResult, OCI_BOTH)[0];

                    $filteredTitleQuery = "select mc2.cookbookTitle
                            from (" . $filteredQuery . "), managedcookbook mc2, consistsof co2
                            where mc2.email = co2.EMAIL AND mc2.cid = co2.cid
                            AND minAverageDifficulty= $filteredValue";
                    $filteredTitleResult = executePlainSQL($filteredTitleQuery);
                    $filteredTitle = OCI_Fetch_Array($filteredTitleResult, OCI_BOTH)[0];
                    echo "<div class='container'>";
                    echo "<a href='cookbookrecipespage.php?cid=" . $cid . "'>" . $filteredTitle . "</a>";
                    echo $filteredValue;
                    echo "</div>";
                }
            }
        } else if ($difficultySelected == 'max') {
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<div class='result'>";

                if (array_key_exists("COOKBOOKTITLE", $row) && array_key_exists("CID", $row)) {
                    $cid = $row["CID"];
                    $title = $row["COOKBOOKTITLE"];
                    $avgQuery = "select avg(r1.DIFFICULTY) as avgDifficulty
                            from MANAGEDCOOKBOOK mc1, RECIPE r1, CONSISTSOF co1
                            where mc1.CID = '" . $cid . "'
                            and co1.cid = '" . $cid . "'
                            and r1.rid = co1.rid
                            and mc1.email = '" . $userEmail . "'
                            and co1.email = '" . $userEmail . "'
                            group by mc1.cid";
                    $avgResult = executePlainSQL($avgQuery);
                    $avg = OCI_Fetch_Array($avgResult, OCI_BOTH)[0];
                    $filteredQuery = "select MAX(avgDifficulty) as minAverageDifficulty
                            from (" . $avgQuery . "), managedcookbook mc2, consistsof co2
                            where mc2.email = co2.EMAIL";
                    $filteredResult = executePlainSQL($filteredQuery);
                    $filteredValue = OCI_Fetch_Array($filteredResult, OCI_BOTH)[0];
                    $filteredTitleQuery = "select mc2.cookbookTitle
                            from (" . $filteredQuery . "), managedcookbook mc2, consistsof co2
                            where mc2.email = co2.EMAIL AND mc2.cid = co2.cid
                            AND minAverageDifficulty= $filteredValue";
                    $filteredTitleResult = executePlainSQL($filteredTitleQuery);
                    $filteredTitle = OCI_Fetch_Array($filteredTitleResult, OCI_BOTH)[0];
                    echo "<div class='container'>";
                    echo "<a href='cookbookrecipespage.php?cid=" . $cid . "'>" . $filteredTitle . "</a>";
                    echo $filteredValue;
                    echo "</div>";
                }
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

<?php require "footer.php";?>