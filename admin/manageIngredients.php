<?php
require "../adminRedirect.php";
require "adminHeader.php";
include_once '../connection.php';
?>
    <h3 class="cookbook-section-header">Manage Ingredients</h3>
<?php
if ($db_conn) {
    $query = "SELECT * FROM INGREDIENT";
    $result = executePlainSQL($query);
    $row = OCI_Fetch_Array($result, OCI_BOTH);
    if (!$row) {
        echo "No ingredients exist";
        exit;
    }
    ?>
    <div class="cookbook-admin-section">
        <h4 class="cookbook-section-header">Edit an Existing Ingredient</h4>
        <table width="600" border="1">
            <tr class="header-row">
                <th>
                    <div class="table-cells">Ingredient Name</div>
                </th>
                <th>
                    <div class="table-cells">Description</div>
                </th>
                <th>
                    <div class="table-cells">Nutritional Facts</div>
                </th>
                <th>
                    <div class="table-cells">Edit</div>
                </th>
            </tr>
            <?php
            $result = executePlainSQL($query);
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                ?>
                <tr>
                    <td>
                        <div class="table-cells"><?php echo $row["INAME"]; ?></div>
                    </td>
                    <td>
                        <div class="table-cells"><?php echo $row["DESCRIPTION"]; ?></div>
                    </td>
                    <td>
                        <div class="table-cells"><?php echo $row["NUTRITIONALFACTS"]; ?></div>
                    </td>
                    <td>
                        <div class="table-cells"><a
                                    href="editIngredient.php?iname=<?php echo str_replace(" ", "-", trim($row["INAME"], " ")); ?>">Edit</a>
                        </div>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
    OCICommit($db_conn);
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
require "../sections/footer.php";
?>