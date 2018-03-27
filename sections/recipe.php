<?php require "header.php";

    $rid = $_GET["rid"];

    if (empty($rid)) {
        echo "<p>No recipe selected.<br>Click one of the menu options on the left.</p>";
    } else {
        include_once '../connection.php';

        if ($db_conn) {

            // TODO: add bookmarking button
    
            // Recipe Title, Cuisine, Difficulty, and Cooking Time
            $query ="SELECT RECIPETITLE, CUISINE, DIFFICULTY, COOKINGTIME FROM RECIPE WHERE RID = '$rid'";
            $result = executePlainSQL($query);
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                if (array_key_exists("RECIPETITLE", $row)) {
                    $title = $row["RECIPETITLE"];

                    if (array_key_exists("CUISINE", $row)) {
                        $cuisine = $row["CUISINE"];
                    }
                    if (array_key_exists("DIFFICULTY", $row)) {
                        switch ($row["DIFFICULTY"]) {
                            case 1:
                                $difficulty = "1 (Very Easy)";
                                break;
                            case 2:
                                $difficulty = "2 (Easy)";
                                break;
                            case 3:
                                $difficulty = "3 (Moderate)";
                                break;
                            case 4:
                                $difficulty = "4 (Hard)";
                                break;
                            case 5:
                                $difficulty = "5 (Very Hard)";
                                break;
                            default:
                                $difficulty = "Unknown";
                                break;
                        }
                    }
                    if (array_key_exists("COOKINGTIME", $row)) {
                        $time = $row["COOKINGTIME"];
                    }
                } else {
                    echo "Cannot find recipe";
                }
            }

            // Ingredients
            $queryIngredients = "SELECT INAME, QUANTITY FROM USES WHERE RID = '$rid'";
            $resultIngredients = executePlainSQL($queryIngredients);
            $ingredients = [];
            while ($row = OCI_Fetch_Array($resultIngredients, OCI_BOTH)) {
                if (array_key_exists("INAME", $row) && array_key_exists("QUANTITY", $row)) {
                // if (array_key_exists("INAME", $row)) {
                    $ingredient = array($row["INAME"]=>$row["QUANTITY"]);
                    // $ingredient = array($row["INAME"]=>'1');
                    $ingredients = array_merge($ingredients, $ingredient);
                } else {
                    echo "no ingredients";
                }
            }

            // Steps
            $querySteps = "SELECT INSTRUCTION FROM INCLUDEDSTEP WHERE RID = '$rid' ORDER BY SID";
            $resultSteps = executePlainSQL($querySteps);
            $steps = [];
            while ($row = OCI_Fetch_Array($resultSteps, OCI_BOTH)) {
                if (array_key_exists("INSTRUCTION", $row)) {
                    array_push($steps, $row["INSTRUCTION"]);
                }
            }

            // Tags
            $queryTags = "SELECT TAGNAME FROM SEARCHABLEBY WHERE RID = '$rid'";
            $resultTags = executePlainSQL($queryTags);
            $tags = [];
            while ($row = OCI_Fetch_Array($resultTags, OCI_BOTH)) {
                if (array_key_exists("TAGNAME", $row)) {
                    array_push($tags, $row["TAGNAME"]);
                }
            }

            OCILogoff($db_conn);

            // Render Recipe
            echo "<h3 class='cookbook-section-header'>$title</h3>";

            echo "<div>";
            echo "<b>Cuisine: </b>$cuisine<br>";
            echo "<b>Difficulty: </b>$difficulty<br>";
            
            if (!empty($time)) {
                echo "<b>Cooking Time (in minutes): </b>$time<br>";
            }

            echo "<br><h4 class='cookbook-section-header'>Ingredients</h4>";
            foreach ($ingredients as $ingredient => $quantity) {
                echo "$quantity $ingredient<br>";
            }

            echo "<br><h4 class='cookbook-section-header'>Instructions</h4>";
            foreach ($steps as $i=>$step) {
                $stepNum = $i + 1;
                echo "$stepNum. $step<br>";
            }

            if (!empty($tags)) {
                echo "<br>";
                foreach ($tags as $tag) {
                    echo "<div class='recipe-tag'>$tag</div>";
                }
            }

            echo "</div>";

        } else {
            echo "cannot connect";
            $e = OCI_Error(); // For OCILogon errors pass no handle
            echo htmlentities($e['message']);
        }
    }

?>

<?php require "footer.php"; ?>