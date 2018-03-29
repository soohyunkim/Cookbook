<?php
require "adminHeader.php";
include_once '../connection.php';
?>
<form name="deleteUsers" action="deleteUsers.php" method="post" OnSubmit="return onDelete();">

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
if (array_key_exists('addUser', $_POST)) {

    $userEmail = $_POST['userEmail'];
    $userPassword = $_POST['userPassword'];
    $userType = $_POST['userType'];

    executePlainSQL("INSERT INTO Users (email, password, type) VALUES ('$userEmail', '$userPassword', '$userType')");

    OCICommit($db_conn);
}
OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
?>

