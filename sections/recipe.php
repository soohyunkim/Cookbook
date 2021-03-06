<?php
require "../normalRedirect.php";
require "header.php";


    echo "<script src='../javascript/recipe.js'></script>";

    $rid = $_GET["rid"];

    function removeSpace($str) {
        return trim($str, " ");
    }

    function replaceSpaceWithDash($str) {
        return preg_replace("/\s/", "-", $str);
    }

    if (empty($rid)) {
        echo "<p>No recipe selected.<br>Click one of the menu options on the left.</p>";
    } else {
        include_once '../connection.php';

        if ($db_conn) {

            $userEmail = $_COOKIE["userEmail"];

            // Bookmark State
            $bookmarkState = "unbookmarked";
            $queryBookmark = "SELECT RID FROM BOOKMARKS WHERE EMAIL = '$userEmail' AND RID = '$rid'";
            $resultBookmark = executePlainSQL($queryBookmark);
            while ($row = OCI_Fetch_Array($resultBookmark, OCI_BOTH)) {
                if (array_key_exists("RID", $row)) {
                    $bookmarkState = "bookmarked";
                }
            }

            // List of Current User's Cookbooks
            $queryCookbooks = "SELECT CID, COOKBOOKTITLE FROM MANAGEDCOOKBOOK WHERE EMAIL ='$userEmail'";
            $resultCookbooks = executePlainSQL($queryCookbooks);
            $cookbooks = [];
            while ($row = OCI_Fetch_Array($resultCookbooks, OCI_BOTH)) {
                if (array_key_exists("CID", $row) && array_key_exists("COOKBOOKTITLE", $row)) {
                    $cookbook = array(removeSpace($row["CID"])=>removeSpace($row["COOKBOOKTITLE"]));
                    $cookbooks = $cookbooks + $cookbook;
                }
            }
    
            // Recipe Title, Cuisine, Difficulty, and Cooking Time
            $queryRecipe = "SELECT RECIPETITLE, CUISINE, DIFFICULTY, COOKINGTIME FROM RECIPE WHERE RID = '$rid'";
            $resultRecipe = executePlainSQL($queryRecipe);
            while ($row = OCI_Fetch_Array($resultRecipe, OCI_BOTH)) {
                if (array_key_exists("RECIPETITLE", $row)) {
                    $title = removeSpace($row["RECIPETITLE"]);

                    if (array_key_exists("CUISINE", $row)) {
                        $cuisine = removeSpace($row["CUISINE"]);
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
                    $ingredient = array(removeSpace($row["INAME"])=>removeSpace($row["QUANTITY"]));
                    $ingredients = $ingredients + $ingredient;
                }
            }

            // Popular ingredient (Division)
            // Select all ingredients which are in all recipes.
            $queryPopularIngredient = "SELECT iName From Ingredient I WHERE NOT EXISTS ((SELECT R.rid FROM Recipe R) MINUS (SELECT U.rid FROM Uses U WHERE U.iName = I.iName))";
            $resultPopularIngredientQuery = executePlainSQL($queryPopularIngredient);
            $resultPopularIngredient = OCI_Fetch_Array($resultPopularIngredientQuery, OCI_BOTH)[0];
            $popularIngredient = trim($resultPopularIngredient, " ");

            // Ingredient Info
            // ingredientName=>[description, nutritionalFacts]
            $ingredientInfo = [];
            foreach ($ingredients as $iName => $qty) {
                $description = "";
                $facts = "";
                $queryInfo = "SELECT DESCRIPTION, NUTRITIONALFACTS FROM INGREDIENT WHERE INAME = q'[$iName]'";
                $resultInfo = executePlainSQL($queryInfo);
                while ($row = OCI_Fetch_Array($resultInfo, OCI_BOTH)) {
                    if (array_key_exists("DESCRIPTION", $row)) {
                        $description = removeSpace($row["DESCRIPTION"]);
                    }
                    if (array_key_exists("NUTRITIONALFACTS", $row)) {
                        $facts = removeSpace($row["NUTRITIONALFACTS"]);
                    }
                }
                $ingredient = array($iName=>array(
                    "description"=>$description,
                    "facts"=>$facts
                ));
                if ($ingredient == $resultPopularIngredient) {
                // put very popular ingredient in
                }
                $ingredientInfo = $ingredientInfo + $ingredient;
            }

            // Steps
            $querySteps = "SELECT INSTRUCTION FROM INCLUDEDSTEP WHERE RID = '$rid' ORDER BY SID";
            $resultSteps = executePlainSQL($querySteps);
            $steps = [];
            while ($row = OCI_Fetch_Array($resultSteps, OCI_BOTH)) {
                if (array_key_exists("INSTRUCTION", $row)) {
                    array_push($steps, removeSpace($row["INSTRUCTION"]));
                }
            }

            // Tags
            $queryTags = "SELECT TAGNAME FROM SEARCHABLEBY WHERE RID = '$rid'";
            $resultTags = executePlainSQL($queryTags);
            $tags = [];
            while ($row = OCI_Fetch_Array($resultTags, OCI_BOTH)) {
                if (array_key_exists("TAGNAME", $row)) {
                    array_push($tags, removeSpace($row["TAGNAME"]));
                }
            }

            OCILogoff($db_conn);

            // RENDER RECIPE

            // Title and Bookmark Icon
            echo "<form id='bookmark-form' method='post' action='helper/handleBookmarking.php'>
                <h3 class='cookbook-section-header'>
                    $title
                    <button type='submit' class='glyphicon glyphicon-bookmark bookmark-icon $bookmarkState' name='bookmarkToggle'></button>
                </h3>
                <input type='hidden' name='rid' value='$rid'>
                <input type='hidden' name='bookmarkState' value='$bookmarkState'>
            </form>";

            // Add to Cookbook Section
            echo
                "<div>
                    <form id='add-to-cookbook-form' method='post' action='helper/handleAddToCookbook.php'>
                        <label>Add to Cookbook:</label>
                        <select class='cookbook-dropdown' name='cookbookDropdown'>
                            <option disabled selected value=''>Select Cookbook...</option>";
            foreach ($cookbooks as $cid => $ctitle) {
                echo "<option value=$cid>$ctitle</option>";
            }
            echo
                        "</select>
                        <input type='hidden' name='rid' value='$rid'>
                        <button type='submit' class='add-to-cookbook-button' name='addToCookbook'>Add
                    </form>
                </div>";

            echo "<div>";
            
            // Cuisine
            echo "<b>Cuisine: </b>$cuisine<br>";

            // Difficulty
            echo "<b>Difficulty: </b>$difficulty<br>";
            
            // Cooking Time
            if (!empty($time)) {
                echo "<b>Cooking Time (in minutes): </b>$time<br>";
            }

            // Ingredients
            echo "<br><h4 class='cookbook-section-header'>Ingredients</h4>";
            foreach ($ingredients as $ingredient => $quantity) {
                $ingId = "ingredient-" . replaceSpaceWithDash($ingredient);
                echo "<span id='$ingId' class='ingredient-text' onClick='onIngredientClick(\"$ingredient\")'>$quantity $ingredient</span><br>";
            }

            // Ingredients Modal
            echo "<div id='ingredient-modal-background' class='hide'><div id='ingredient-modal'>";
            foreach ($ingredientInfo as $ingredient => $info) {
                $ingTitle = ucwords($ingredient);
                $description = $info["description"];
                $facts = $info["facts"];
                $ingId = "info-" . replaceSpaceWithDash($ingredient);
                echo "<div id='$ingId' class='ingredient-info'>";
                echo "<h4 class='ingredient-modal-title'>$ingTitle</h4>";
                if ($popularIngredient == $ingredient) {
                    echo "<div class='popular-ingredient'>Popular Ingredient</div>";
                }
                echo "<div class='ingredient-modal-details'>";
                if (!empty($description)) {
                    echo "<b>Description:</b> $description<br>";
                }
                if (!empty($facts)) {
                    echo "<b>Nutritional Facts:</b> $facts<br>";
                }
                echo "</div></div>";
            }
            echo "<button class='ingredient-close' type='button' onClick='onIngredientClose()'>Close</button>";
            echo "</div></div>";

            // Instructions
            echo "<br><h4 class='cookbook-section-header'>Instructions</h4>";
            foreach ($steps as $i=>$step) {
                $stepNum = $i + 1;
                echo "$stepNum. $step<br>";
            }

            // Tags
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