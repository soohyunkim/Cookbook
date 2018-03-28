<?php
require "header.php";
?>

<h3 class="cookbook-section-header">Cookbooks</h3>
<p>Manage organized lists of recipes in your own custom cookbooks.</p>

<p>Enter title and description to create your cookbook:</p>
<form method="post" action="cookbooks.php">

    <!-- Cookbook Title -->
    <div class="cookbook-create-section">
        <label>Title:</label>
        <input type="text" id="cookbook-create-title" name="cookbookTitle">
    </div>
    <!-- Cookbook Title -->
    <div class="cookbook-create-section">
        <label>Description:</label>
        <input type="text" id="cookbook-create-description" name="description">
    </div>
    <!-- Submit Button -->
    <div class="cookbook-create-section">
        <button type="submit" onClick="submitForm()" name="uploadRecipe">Create Cookbook</button>
    </div>
</form>

<?php

include_once '../connection.php';

// Connect Oracle...
if ($db_conn) {
    if (array_key_exists('cookbookSubmit', $_POST)) {
        $cookbookInfo = array(
            ":cookbookTitle" => $_POST['cookbookTitle'],
            ":description" => $_POST['description'],
            ":cid" => uniqid(),
            ":email" => "emily604@sample.com" //TODO: change this to grab the current user's email
        );
        $alltuples = array(
            $cookbookInfo
        );
        executeBoundSQL("INSERT INTO MANAGEDCOOKBOOK VALUES (:cookbookTitle, :description, :cid, :email)", $alltuples);

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

<?php require "footer.php"; ?>