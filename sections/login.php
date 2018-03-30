<?php
include_once '../connection.php';
if ($db_conn) {

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $result = executePlainSQL("SELECT TYPE FROM USERS WHERE EMAIL = '$email' AND PASSWORD = '$password'");
        $row = OCI_Fetch_Array($result, OCI_BOTH);

        if ($row) {
            $userType = trim($row[0], " ");
            setcookie("userEmail", $email, 0, '/');
            setcookie("userType", $userType, 0, '/');
            if ($userType === 'normal') {
                header("location: search.php");
                exit;
            } else if ($userType === 'admin') {
                header("location: ../admin/manageUsers.php");
                exit;
            } else {
                echo "user type error:" . $userType . ".";
            }
        }else {
            echo "wrong password or username";
            header("location: ../index.php");
            exit;
        }
        OCICommit($db_conn);
    }
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
}
?>