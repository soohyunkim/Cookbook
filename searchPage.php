<p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>
<p>Search:</p>
<form method="POST" action="searchPage.php">
    <p> <input type=""
        <input type="text" name="searchText" size="100">
        <!--define two variables to pass the value-->
        <input type="submit" value="search" name="searchSubmit"></p>
</form>


<?php
include_once 'connection.php';
if ($db_conn) {
    $searchString = $_POST['searchText'];
    $result=  executePlainSQL(
        "SELECT RECIPETITLE 
        FROM RECIPE
        WHERE LOWER(RECIPETITLE) LIKE '%" .  $searchString  . "%'");
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<div class='result'>";
        if (array_key_exists("RECIPETITLE", $row)) {
            echo "<p>" . $row["RECIPETITLE"] . "</p>";       
        } else {
            echo "<p>There are no recipes that match your search.</p>";
        }
    }
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
?>
