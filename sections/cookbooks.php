<?php
require "../normalRedirect.php";
require "header.php";
?>
    <script src="../javascript/createCookbook.js"></script>

    <h3 class="cookbook-section-header">Create a New Cookbook</h3>
    <p>Enter both a title and a description to create a new cookbook.</p>
    <form id="cookbook-create-form" method="post" action="cookbooks.php" onSubmit="return submitCookbookForm();" >

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
            <button type="submit" name="uploadCookbook">Create Cookbook</button>
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
            echo "<h3 class='cookbook-section-header'>My Cookbooks</h3>";
            
            echo "<form action='cookbooks.php' method='post' id='cookbook-difficulty-form'>";
            echo "<div id='cookbook-difficulty-menu'>";
            echo "<b>Filter by Difficulty:</b>";
            echo "<label class='radio-inline'><input type='radio' id='difficulty-radio-uncategorized' name='difficulty'";
            if (isset($_POST['difficulty']) && $_POST['difficulty']=='uncategorized') {
                echo "checked='checked'";
            }
            echo "value='uncategorized'> Uncategorized </label>";
            echo "<label class='radio-inline'><input type='radio' name='difficulty'";
            if (isset($_POST['difficulty']) && $_POST['difficulty']=='max') {
                echo "checked='checked'";
            }
            echo "value='max' id='difficulty-radio-max'> Maximum </label>";
            echo "<label class='radio-inline'><input type='radio' name='difficulty'";
            if (isset($_POST['difficulty']) && $_POST['difficulty']=='min') {
                echo "checked='checked'";
            }
            echo "value='min' id='difficulty-radio-min'> Minimum </label>";
            echo "</div>";
            echo "<button type='submit' class='filter-difficulty-button' name='filterSubmit'>Show Cookbooks</button>";
            echo "</form>";

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
                    $cid = trim($row['CID'], " ");
                    $result = executePlainSQL("SELECT COOKBOOKTITLE FROM MANAGEDCOOKBOOK WHERE CID='$cid'");
                    $title = trim(OCI_Fetch_Array($result, OCI_BOTH)[0], " ");
                    $avgDifficulty = substr(trim($row['AVGDIFFICULTY'], " "), 0, 3);

                    echo "<div>
                            <form id='delete-cookbook-form' class='remove-cookbook-form remove-cb-recipe-form' method='post' action='helper/handleDeleteCookbook.php'>
                                    <a href='cookbookrecipespage.php?cid=$cid'>$title</a>
                                    <span class='cookbook-avg-difficulty'>(Difficulty: $avgDifficulty)</span>
                                    <button type='submit' class='btn remove-cb-recipe-button' name='deleteCookbookSubmit'>x</button>
                                    <input type='hidden' name='cid' value='$cid'>
                            </form>
                        </div>";
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
                $minCID = trim($minRow["FIRSTCID"], " ");
                $minValue = substr(trim($minRow["AVGDIFFICULTY"], " "), 0, 3);

                $result = executePlainSQL("SELECT COOKBOOKTITLE FROM MANAGEDCOOKBOOK WHERE CID='$minCID'");
                $minTitle = OCI_Fetch_Array($result, OCI_BOTH)[0];

                echo "<div>
                        <form id='delete-cookbook-min-form' class='remove-cookbook-min-form remove-cb-recipe-form' method='post' action='helper/handleDeleteCookbook.php'>
                                <a href='cookbookrecipespage.php?cid=$minCID'>$minTitle</a>
                                <span class='cookbook-avg-difficulty'>(Difficulty: $minValue)</span>
                                <button type='submit' class='btn remove-cb-recipe-button' name='deleteCookbookMinSubmit'>x</button>
                                <input type='hidden' name='minCID' value='$minCID'>
                        </form>
                      </div>";
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
                $maxCID = trim($maxRow["FIRSTCID"], " ");
                $maxValue = substr(trim($maxRow["AVGDIFFICULTY"], " "), 0, 3);

                $result = executePlainSQL("SELECT COOKBOOKTITLE FROM MANAGEDCOOKBOOK WHERE CID='$maxCID'");
                $maxTitle = OCI_Fetch_Array($result, OCI_BOTH)[0];

                echo "<div>
                        <form id='delete-cookbook-max-form' class='remove-cookbook-max-form remove-cb-recipe-form' method='post' action='helper/handleDeleteCookbook.php'>
                                <a href='cookbookrecipespage.php?cid=$maxCID'>$maxTitle</a>
                                <span class='cookbook-avg-difficulty'>(Difficulty: $maxValue)</span>
                                <button type='submit' class='btn remove-cb-recipe-button' name='deleteCookbookMaxSubmit'>x</button>
                                <input type='hidden' name='maxCID' value='$maxCID'>
                        </form>
                      </div>";
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