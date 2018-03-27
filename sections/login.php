<?php
include_once '../connection.php';
?>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <script src="javascript/login.js"></script>
</head>

<body>
<h3 class="login-header">Cookbook Database Login</h3>
<form id="login-form" method="post" action="sections/login.php">

    <!-- Login Email -->
    <div class="login-field-wrap">
        <label>Email:</label>
        <input type="text" class="login-input" name="email">
    </div>

    <!-- Login Password -->
    <div class="login-field-wrap">
        <label>Password:</label>
        <input type="password" class="login-input" name="password">
    </div>

    <!-- Submit Button -->
    <button type="submit" onClick="loginClicked()" name="login" class="login-button">Login</button>

</form>
<?php
if ($db_conn) {

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $result = executePlainSQL("SELECT * FROM USERS WHERE EMAIL = '$email' AND PASSWORD = '$password'");
        $row = OCI_Fetch_Array($result, OCI_BOTH);

        if ($row) {
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['timeStart'] = time();
            header("location: search.php");
        } else {
            echo "wrong password or username";
            header("location: ../index.php");
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
</body>
</html>