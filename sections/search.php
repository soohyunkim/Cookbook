<?php
require "header.php";
?>

<h3 class="cookbook-section-header">Search</h3>
<p>Search for recipes by title, tag, or cuisine type.</p>

<form id="cookbook-search-form" method="post" action="search.php">
    <div class="cookbook-search-section">
        <label>Search By: </label>
        <select name="searchBy" class="cookbook-search">
            <option value="title">Title</option>
            <option value="tags">Tags</option>
            <option value="cuisine">Cusine</option>
        </select>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div id="search-container"> 
                    <div class="input-group cookbook-search-section">
                        <input type="text" class="form-control" name="searchText" placeholder="Search" >
                        <span class="input-group-addon">
                            <button type="submit" value="search" name="searchSubmit">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>  
                        </span>
                    </div>
                </div>
            </div>
        </div>
</div>
    </div>
</form>
<?php
    include_once '../connection.php';
    if ($db_conn) {
        // hardcoded to get all for now, modify to use LIKE
        $searchSubmit = $_POST["searchSubmit"];
        if (!is_null($searchSubmit)) {
            $searchText = $_POST["searchText"];
            $searchBy = $_POST["searchBy"];
            switch ($searchBy) {
                //searches are NOT case sensitive
                case "title":
                    $query = "SELECT RECIPETITLE, RID FROM RECIPE WHERE UPPER(RECIPETITLE) LIKE UPPER(q'[%$searchText%]')";
                    break;
                case "tags":
                    $query = "SELECT DISTINCT RECIPETITLE, r.RID FROM RECIPE r, SEARCHABLEBY s WHERE s.RID = r.RID AND UPPER(s.TAGNAME) LIKE UPPER(q'[%$searchText%]')";
                    break;
                case "cuisine":
                    $query = "SELECT RECIPETITLE, RID FROM RECIPE WHERE UPPER(CUISINE) LIKE UPPER('%" . $searchText . "%')";
                    break;
            }
            $result=  executePlainSQL($query);
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<div class='result'>";
                if (array_key_exists("RECIPETITLE", $row) && array_key_exists("RID", $row)) {
                    $rid = $row["RID"];
                    echo "<p><a href='recipe.php?rid=".$rid."'>" . $row["RECIPETITLE"] . "</a></p>";
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

<?php require "footer.php"; ?>