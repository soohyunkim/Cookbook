<?php
include_once '../../connection.php';

if ($db_conn) {

    if (array_key_exists('bookmarkToggle', $_POST)) {

        $userEmail = $_COOKIE["userEmail"];
        $rid = $_POST["rid"];
        $bookmarkState = $_POST["bookmarkState"];

        if ($bookmarkState == "bookmarked") {
            // if the recipe is already bookmarked, remove it from the table
            $query = "DELETE FROM Bookmarks WHERE email = '$userEmail' AND rid = '$rid'";
        } else {
            // if the recipe isn't bookmarked, add it to the table
            $query = "INSERT INTO Bookmarks(email, rid) VALUES ('$userEmail', '$rid')";
        }
        executePlainSQL($query);

        OCICommit($db_conn);
    }
    OCILogoff($db_conn);

    if (!empty($rid)) {
        header("Location: ../recipe.php?rid=$rid");
    } else {
        header("Location: ../bookmarks.php");
    }

} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}

?>