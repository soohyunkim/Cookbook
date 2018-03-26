<p>Search:</p>
<form method="POST" action="searchPage.php">
    <p><select name="searchBy">
            <option value="title">Title</option>
            <option value="tags">Tags</option>
            <option value="cuisine">Cusine</option>
        </select>
        <input type="text" name="searchText" size="100">
        <!--define two variables to pass the value-->
        <input type="submit" value="search" name="searchSubmit"></p>
</form>


<?php
    include_once 'connection.php';

    if ($db_conn) {
        // hardcoded to get all for now, modify to use LIKE
        $searchSubmit = $_POST["searchSubmit"];
        if (!is_null($searchSubmit)) {

            $searchText = $_POST["searchText"];
            $searchBy = $_POST["searchBy"];

            switch ($searchBy) {
                //searches are NOT case sensitive
                case "title":
                    $query = "SELECT RECIPETITLE FROM RECIPE WHERE UPPER(RECIPETITLE) LIKE UPPER('%" . $searchText . "%')";
                    break;
                case "tags":
                    $query = "SELECT DISTINCT RECIPETITLE FROM RECIPE r, SEARCHABLEBY s WHERE s.RID = r.RID AND UPPER(s.TAGNAME) LIKE UPPER('%" . $searchText . "%')";
                    break;
                case "cuisine":
                    $query = "SELECT RECIPETITLE FROM RECIPE WHERE UPPER(CUISINE) LIKE UPPER('%" . $searchText . "%')";
                    break;
            }
            $result=  executePlainSQL($query);

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<div class='result'>";
                if (array_key_exists("RECIPETITLE", $row)) {
                    //TODO: Hyperlink to recipe page based on the search
                    echo "<p><a href=\"\">" . $row["RECIPETITLE"] . "</a></p>";
                } else {
                    echo "<p>There are no recipes that match your search.</p>";
                }
            }
            OCILogoff($db_conn);
        }
    } else {
        echo "cannot connect";
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
    }
?>
