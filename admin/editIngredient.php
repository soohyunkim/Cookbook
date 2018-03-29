<?php require "adminHeader.php"; ?>

<h3 class="cookbook-section-header">Edit Ingredient</h3>

<?php
    $iName = str_replace("-", " ", $_GET["iname"]);
    
    if (empty($iName)) {
        echo "<p>No ingredient selected.</p>";
    } else {
        include_once '../connection.php';

        if ($db_conn) {
            $query = "SELECT * FROM INGREDIENT WHERE INAME = '$iName'";
            $result = executePlainSQL($query);
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                if (array_key_exists("INAME", $row)) {
                    $renderForm = true;
                    if (array_key_exists("DESCRIPTION", $row)) {
                        $description = trim($row["DESCRIPTION"], " ");
                    }
                    if (array_key_exists("NUTRITIONALFACTS", $row)) {
                        $facts = trim($row["NUTRITIONALFACTS"], " ");
                    }
                }
            }

            // render form if ingredient is valid
            if ($renderForm) {
                $title = ucwords($iName);
                echo "<div class='cookbook-admin-section'>";
                echo "<h4 class='cookbook-section-header'>" . ucwords($iName) . "</h4>";
                echo
                "<form id='edit-ingredient-form' method='post' action='helper/saveIngredient.php'>
                    <input type='hidden' name='iName' value='$iName'>

                    <div class='edit-ingredient-section'>
                        <label>Description:</label><br>
                        <input size=70 maxlength=500 type='text'name='ingredientDescription' value='$description'>
                    </div>

                    <div class='edit-ingredient-section'>
                        <label>Nutritional Facts:</label><br>
                        <input size=70 maxlength=500 type='text'name='ingredientFacts' value='$facts'>
                    </div>

                    <button type='submit' id='save-ingredient-button' name='saveIngredient'>Save</button>

                </form>";
                echo "</div>";
            }
            
            // don't render form if ingredient is not in the database
            else {
                echo "<p>Cannot find ingredient '$iName'.<br>Please create a new ingredient instead.</p>";
            }

        } else {
            echo "cannot connect";
            $e = OCI_Error(); // For OCILogon errors pass no handle
            echo htmlentities($e['message']);
        }
    }

?>

<?php require "../sections/footer.php"; ?>