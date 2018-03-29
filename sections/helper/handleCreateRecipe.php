<?php
    include_once '../../connection.php';

    if ($db_conn) {

        if (array_key_exists('uploadRecipe', $_POST)) {

            $recipeId = uniqid();
            $recipeTitle = $_POST['title'];
            $cuisineType = $_POST['cuisine'];
            $difficulty = $_POST['difficulty'];
            $cookingTime = $_POST['time'];
            $ingredients = $_POST['ingredient'];
            $quantities = $_POST['quantity'];
            $instructions = $_POST['instruction'];
            $tags = $_POST['tags'];

            executePlainSQL("INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('$recipeId', q'[$recipeTitle]', '$cuisineType', $difficulty, " . ($cookingTime == '' ? 'NULL' : $cookingTime) . ")");

            foreach ($ingredients as $index => $ingredientRaw) {
                if ($ingredientRaw) {
                    $ingredient = strtolower($ingredientRaw);
                    $result = executePlainSQL("SELECT * FROM INGREDIENT WHERE INAME = q'[$ingredient]'");
                    $row = OCI_Fetch_Array($result, OCI_BOTH);
                    if (!is_null($row)) {
                        executePlainSQL("INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES (q'[$ingredient]', NULL, NULL)");
                    }
                    executePlainSQL("INSERT INTO Uses(rid, iName, quantity) VALUES ('$recipeId', q'[$ingredient]', q'[$quantities[$index]]')");
                }
            }

            foreach ($instructions as $stepNum => $instruction) {
                executePlainSQL("INSERT INTO IncludedStep (sid, rid, instruction) VALUES ($stepNum+1, '$recipeId', q'[$instruction]')");
            }

            foreach ($tags as $tag) {
                $result = executePlainSQL("SELECT * FROM TAG WHERE TAGNAME = q'[$tag]'");
                $row = OCI_Fetch_Array($result, OCI_BOTH);
                if (!is_null($row)) {
                    executePlainSQL("INSERT INTO Tag(tagName) VALUES (q'[$tag]')");
                }
                executePlainSQL("INSERT INTO SearchableBy(tagName, rid) VALUES (q'[$tag]', '$recipeId')");
            }
            OCICommit($db_conn);
        }
        OCILogoff($db_conn);

        if (!empty($recipeId)) {
            header("Location: ../recipe.php?rid=$recipeId");
        } else {
            header("Location: ../create.php");
        }

    } else {
        echo "cannot connect";
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
    }

?>