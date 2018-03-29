<?php
require "adminHeader.php";
include_once '../connection.php';
?>
<script src="../javascript/addUser.js"></script>

<form id="add-user-form" method="post" onSubmit=" return validateUserForm();" action="helper/handleAddUser.php">

    <!-- User Email -->
    <div class="add-user-section">
        <label>User Email:</label>
        <input type="text" id="add-user-email" name="userEmail">
    </div>

    <!-- User Password -->
    <div class="add-user-section">
        <label>User Password:</label>
        <input type="text" id="add-user-password" name="userPassword">
    </div>

    <!-- User Type -->
    <div class="add-user-section">
        <label>User Type:</label>
        <select class="add-user-type" name="userType">
            <option value="normal">Normal</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <!-- Submit Button -->
    <div class="add-user-section">
        <button type="submit" name="addUser">Add User</button>
    </div>
</form>

<form name="deleteUsers" action="helper/deleteUsers.php" method="post" OnSubmit="return onDelete();">

    <?php
    if ($db_conn) {
    $query = "SELECT * FROM USERS";
    $result = executePlainSQL($query);
    $row = OCI_Fetch_Array($result, OCI_BOTH);
    if (!$row) {
        echo "No users exist";
        exit;
    }
    ?>
    <table width="600" border="1">
        <tr>
            <th>
                <div class="table-cells">User Email</div>
            </th>
            <th>
                <div class="table-cells">Password</div>
            </th>
            <th>
                <div class="table-cells">User Type</div>
            </th>
            <th>
                <div class="table-cells">Delete</div>
            </th>
        </tr>
        <?php
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            ?>
            <tr>
                <td>
                    <div class="table-cells"><?php echo $row["EMAIL"]; ?></div>
                </td>
                <td>
                    <div class="table-cells"><?php echo $row["PASSWORD"]; ?></div>
                </td>
                <td>
                    <div class="table-cells"><?php echo $row["TYPE"]; ?></div>
                </td>
                <td>
                    <div class="table-cells"><input type="checkbox" name="chkDel[]" value="<?php echo $row["EMAIL"]; ?>"></div>
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
require "../sections/footer.php"; ?>
?>

