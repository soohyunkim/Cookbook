<?php
require "header.php";
?>
    <script src="../javascript/createCookbook.js"></script>

    <h3 class="cookbook-section-header">Create a cookbook: </h3>
    <p>Enter title and description to create your cookbook:</p>
    <form id="cookbook-create-form" method="post" action="cookbooks.php">

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

        $countQuery = "SELECT DISTINCT COUNT(CID) AS CIDCOUNT FROM MANAGEDCOOKBOOK WHERE EMAIL = '" . $userEmail . "'";
        $countResult = executePlainSQL($countQuery);
        $countRow = OCI_Fetch_Array($countResult, OCI_BOTH)[0];

        if ($countRow > 0) {
            echo "<h3 class='cookbook-section-header'>My cookbooks: </h3>";
            echo "<div class='container'>";
            echo "<form action='cookbooks.php' method='post'>Filter by Difficulty: ";
            echo "<label class='radio-inline'><input type='radio' name='difficulty' value='uncategorized'> Uncategorized </label>";
            echo "<label class='radio-inline'><input type='radio' name='difficulty' value='max'> Maximum </label>";
            echo "<label class='radio-inline'><input type='radio' name='difficulty' value='min'> Minimum </label>";
            echo "<input type='submit' name='filterSubmit'value='submit'>";
            echo "</form>";
            echo "</div>";

        } else {
            echo "<p>You don't have any cookbooks :(. Create one!</p>";
        }

        if (array_key_exists('filterSubmit', $_POST)) {

            $difficultySelected = $_POST["difficulty"]; //Want to grab the difficulty sorting, and run the query accordingly

            if ($difficultySelected == 'uncategorized') {
                echo "<div class='result'>";
                $avgQuery = "SELECT mc1.cid, avg(r1.DIFFICULTY) AS avgDifficulty
                            FROM MANAGEDCOOKBOOK mc1, RECIPE r1, CONSISTSOF co1
                            WHERE mc1.cid = co1.cid
                            AND r1.rid = co1.rid
                            AND mc1.email = '" . $userEmail . "'
                            AND co1.email = '" . $userEmail . "'
                            GROUP BY mc1.cid";
                $avgResult = executePlainSQL($avgQuery);
                while ($row = OCI_Fetch_Array($avgResult, OCI_BOTH)) {
                    $cid = $row['CID'];
                    $result = executePlainSQL("SELECT COOKBOOKTITLE FROM MANAGEDCOOKBOOK WHERE CID='$cid'");
                    $title = OCI_Fetch_Array($result, OCI_BOTH)[0];
                    echo "<div class='container'>";
                    echo "<a href='cookbookrecipespage.php?cid=" . $cid . "'>" . $title . "</a>";
                    echo $row["AVGDIFFICULTY"];
                    echo "</div>";
                }
                echo "</div>";
            } else if ($difficultySelected == 'min') {
                echo "<div class='result'>";
                $avgQuery = "SELECT mc1.cid AS FIRSTCID, avg(r1.DIFFICULTY) AS avgDifficulty
                            FROM MANAGEDCOOKBOOK mc1, RECIPE r1, CONSISTSOF co1
                            WHERE mc1.cid = co1.cid
                            AND r1.rid = co1.rid
                            AND mc1.email = '" . $userEmail . "'
                            AND co1.email = '" . $userEmail . "'
                            GROUP BY mc1.cid";
                $minQuery = "SELECT * FROM ($avgQuery) WHERE AVGDIFFICULTY = (SELECT MIN(avgDifficulty) AS minAverageDifficulty FROM ($avgQuery), managedcookbook mc2, consistsof co2 WHERE mc2.email = co2.EMAIL)";
                $minResult = executePlainSQL($minQuery);
                $minRow = OCI_Fetch_Array($minResult, OCI_BOTH);
                $minCID = $minRow["FIRSTCID"];
                $minValue = $minRow["AVGDIFFICULTY"];

                $result = executePlainSQL("SELECT COOKBOOKTITLE FROM MANAGEDCOOKBOOK WHERE CID='$minCID'");
                $minTitle = OCI_Fetch_Array($result, OCI_BOTH)[0];

                echo "<div class='container'>";
                echo "<a href='cookbookrecipespage.php?cid=" . $minCID . "'>" . $minTitle . "</a>";
                echo $minValue;
                echo "</div>";
            } else if ($difficultySelected == 'max') {
                echo "<div class='result'>";
                $avgQuery = "SELECT mc1.cid AS FIRSTCID, avg(r1.DIFFICULTY) AS avgDifficulty
                            FROM MANAGEDCOOKBOOK mc1, RECIPE r1, CONSISTSOF co1
                            WHERE mc1.cid = co1.cid
                            AND r1.rid = co1.rid
                            AND mc1.email = '" . $userEmail . "'
                            AND co1.email = '" . $userEmail . "'
                            GROUP BY mc1.cid";
                $maxQuery = "SELECT * FROM ($avgQuery) WHERE AVGDIFFICULTY = (SELECT MAX(avgDifficulty) AS maxAverageDifficulty FROM ($avgQuery), managedcookbook mc2, consistsof co2 WHERE mc2.email = co2.EMAIL)";
                $maxResult = executePlainSQL($maxQuery);
                $maxRow = OCI_Fetch_Array($maxResult, OCI_BOTH);
                $maxCID = $maxRow["FIRSTCID"];
                $maxValue = $maxRow["AVGDIFFICULTY"];

                $result = executePlainSQL("SELECT COOKBOOKTITLE FROM MANAGEDCOOKBOOK WHERE CID='$maxCID'");
                $maxTitle = OCI_Fetch_Array($result, OCI_BOTH)[0];

                echo "<div class='container'>";
                echo "<a href='cookbookrecipespage.php?cid=" . $maxCID . "'>" . $maxTitle . "</a>";
                echo $maxValue;
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

require "footer.php"; ?>