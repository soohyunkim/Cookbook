<?php
require "adminHeader.php";
include_once '../connection.php';
?>
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
        <tr>
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
    <input type="submit" name="deleteButton" value="Delete">
    </form>
    <?php
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
require "../sections/footer.php";
?>