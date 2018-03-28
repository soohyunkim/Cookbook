<p>Enter title and description to create your cookbook:</p>
<form method="POST" action="createCookbookPage.php">
    <p> Title: &nbsp; <input type="text" name="cookbookTitle" size="20">
        <br>
        Description: &nbsp; <input type="text" name="description" size="40">
        <br>
        <input type="submit" value="create" name="cookbookSubmit"></p>
</form>

<?php

include_once 'connection.php';

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



