<?php
require "adminHeader.php";
include_once '../connection.php';
?>
<h3 class="cookbook-section-header">Manage Recipes</h3>
<div class="cookbook-admin-section">
    <h4 class="cookbook-section-header">Remove an Existing Recipe</h4>
    <form name="deleteRecipe" action="helper/deleteRecipes.php" method="post" OnSubmit="return onDelete();">
<?php
if ($db_conn) {
    $query = "SELECT RID, RECIPETITLE FROM RECIPE";
    $result = executePlainSQL($query);
    $row = OCI_Fetch_Array($result, OCI_BOTH);
    if (!$row) {
        echo "No recipes exist";
        exit;
    }
    ?>
    <table width="600" border="1">
        <tr class="header-row">
            <th>
                <div class="table-cells">Recipe ID</div>
            </th>
            <th>
                <div class="table-cells">Recipe Title</div>
            </th>
            <th>
                <div class="table-cells">Delete</div>
            </th>
        </tr>
        <?php
        $result = executePlainSQL($query);
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            ?>
            <tr>
                <td>
                    <div class="table-cells"><?php echo $row["RID"]; ?></div>
                </td>
                <td>
                    <div class="table-cells"><?php echo $row["RECIPETITLE"]; ?></div>
                </td>
                <td>
                    <div class="table-cells"><input type="checkbox" name="chkDel[]"
                                                    value="<?php echo $row["RID"]; ?>"></div>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
    OCICommit($db_conn);
    ?>
    <button type="submit" class="delete-button" name="deleteButton">Delete Selected</button>
    </form>
    </div>
    <?php
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
require "../sections/footer.php";
?>