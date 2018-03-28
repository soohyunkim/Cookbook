<?php
require "header.php";
?>


    <h3 class="cookbook-section-header">Bookmarks</h3>
    <p>View your bookmarked recipes.</p>

<?php
include_once '../connection.php';
if ($db_conn) {
    $userEmail = $_COOKIE['userEmail'];
    $query = "SELECT r.RID, r.RECIPETITLE FROM BOOKMARKS b, RECIPE r WHERE b.RID = r.RID AND EMAIL = '$userEmail'";
    $result = executePlainSQL($query);

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<div class='result'>";
        if (array_key_exists("RECIPETITLE", $row)) {
            $rid = $row["RID"];
            $title = $row["RECIPETITLE"];
            echo "<a href='recipe.php?rid=" . $rid . "'>" . $title . "</a>";
        } else {
            echo "<p>You have no bookmarked recipes.</p>";
        }
        echo "</div>";
    }
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
?>

<?php require "footer.php"; ?>