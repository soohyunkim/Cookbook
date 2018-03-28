<?php require "header.php"; ?>

    <script src="../javascript/createRecipe.js"></script>

    <h3 class="cookbook-section-header">Create a New Recipe</h3>
    <p>Upload a new recipe to the database to share it with other users.</p>

    <form id="cookbook-create-form" method="post" action="create.php">

        <!-- Recipe Name -->
        <div class="cookbook-create-section">
            <label>Recipe Name:</label>
            <input type="text" id="cookbook-create-title" name="title">
        </div>

        <!-- Cuisine -->
        <div class="cookbook-create-section">
            <label>Cuisine:</label>
            <select class="cookbook-create-cuisine" name="cuisine">
                <option value="Other">Other</option>
                <option value="Chinese">Chinese</option>
                <option value="Korean">Korean</option>
                <option value="Indian">Indian</option>
                <option value="Italian">Italian</option>
                <option value="Japanese">Japanese</option>
                <option value="American">American</option>
                <option value="Thai">Thai</option>
                <option value="Ethiopian">Ethiopian</option>
                <option value="French">French</option>
                <option value="Brazilian">Brazilian</option>
                <option value="Mexican">Mexican</option>
            </select>
        </div>

        <!-- Difficulty -->
        <div class="cookbook-create-section">
            <label>Difficulty:</label>
            <select class="cookbook-create-difficulty" name="difficulty">
                <option value="1">1 (Very Easy)</option>
                <option value="2">2 (Easy)</option>
                <option value="3">3 (Moderate)</option>
                <option value="4">4 (Hard)</option>
                <option value="5">5 (Very Hard)</option>
            </select>
        </div>

        <!-- Cooking Time -->
        <div class="cookbook-create-section cookbook-create-time">
            <label>Cooking Time (in minutes):</label>
            <input type="number" name="time" min="0">
        </div>

        <!-- Ingredients -->
        <div class="cookbook-create-section">
            <label>Ingredients:</label>
            <div id="cookbook-create-ingredients">
                <div class="cookbook-create-ingredient">
                    <input type="text" class="ingredient_qty" name="quantity[]" placeholder="Quantity">
                    <input type="text" class="ingredient_desc" name="ingredient[]" placeholder="Ingredient">
                </div>
            </div>
            <button type="button" onClick="addIngredientField()">Add Ingredient</button>
        </div>

        <!-- Steps -->
        <div class="cookbook-create-section">
            <label>Steps:</label>
            <div id="cookbook-create-steps">
                <div class="cookbook-create-step">
                    <label>Step 1:</label>
                    <input type="text" id="step-1" name="instruction[]">
                </div>
            </div>
            <button type="button" onClick="addStepField()">Add Step</button>
        </div>

        <!-- Tags -->
        <div class="cookbook-create-section">
            <label>Tags:</label>
            <div id="cookbook-create-tags"></div>
            <button type="button" onClick="addTagField()">Add Tag</button>
        </div>

        <!-- Submit Button -->
        <div class="cookbook-create-section">
            <button type="submit" onClick="submitForm()" name="uploadRecipe">Upload Recipe</button>
        </div>

    </form>
<?php
include_once '../connection.php';

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

        executePlainSQL("INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('$recipeId', '$recipeTitle', '$cuisineType', $difficulty, " . ($cookingTime == '' ? 'NULL' : $cookingTime) . ")");

        foreach ($ingredients as $index => $ingredient) {
            if ($ingredient) {
                $result = executePlainSQL("SELECT * FROM INGREDIENT WHERE INAME = '$ingredient'");
                $row = OCI_Fetch_Array($result, OCI_BOTH);
                if (!is_null($row)) {
                    executePlainSQL("INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('$ingredient', NULL, NULL, NULL)");
                }
                executePlainSQL("INSERT INTO Uses(rid, iName, quantity) VALUES ('$recipeId', '$ingredient', '$quantities[$index]')");
            }
        }

        foreach ($instructions as $stepNum => $instruction) {
            executePlainSQL("INSERT INTO IncludedStep (sid, rid, instruction) VALUES ($stepNum+1, '$recipeId', '$instruction')");
        }

        foreach ($tags as $tag) {
            $result = executePlainSQL("SELECT * FROM TAG WHERE TAGNAME = '$tag'");
            $row = OCI_Fetch_Array($result, OCI_BOTH);
            if (!is_null($row)) {
                executePlainSQL("INSERT INTO Tag(tagName) VALUES ('$tag')");
            }
            executePlainSQL("INSERT INTO SearchableBy(tagName, rid) VALUES ('$tag', '$recipeId')");
        }
        OCICommit($db_conn);
    }
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}

?>
<?php require "footer.php"; ?>
