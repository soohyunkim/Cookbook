<?php
include_once 'connection.php';

//

// Connect Oracle...
if ($db_conn) {

    if (array_key_exists('insertRecipe', $_POST)) {
        //Getting the values from user and insert data into the table
        $recipeInfo = array(

            ":recipeId" => uniqid(),
            ":recipeTitle" => $_POST['recipeTitle'],
            ":cuisineType" => $_POST['cuisineType'],
            ":difficulty" => $_POST['difficulty'],
            ":cookingTime" => $_POST['cookingTime'],
        );
        $alltuples = array(
            $recipeInfo
        );
        executeBoundSQL("INSERT INTO RECIPE VALUES (:recipeId, :recipeTitle, :cuisinType, :difficulty, :cookingTime)", $alltuples);

        // have html for this as <input type="text" name="ingredient[quantity][]" />
        foreach($_POST['ingredients'] as $quantity=>$ingredient) {
            executePlainSQL("INSERT INTO USES VALUES (:recipeId, $ingredient, $quantity)");
        }

        // have html for this as <input type="text" name="instruction[stepNumber][]" />
        foreach($_POST['instruction'] as $stepId=>$instruction) {
            executeBoundSQL("INSERT INTO INCLUDEDSTEP VALUES ($stepId, :recipeId, $instruction)", $alltuples);
        }

        // have html for this as <input type="text" name="tags[]" />
        foreach($_POST['tags'] as $tag) {
            executePlainSQL("INSERT INTO SEARCHABLEBY VALUES ($tag, :recipeId)");
        }

        OCICommit($db_conn);
    }

    //Commit to save changes...
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}

?>

