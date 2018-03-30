<?php
include_once 'connection.php';
if ($_COOKIE['userEmail'] && $_COOKIE['userType'] == 'normal') {
    header("location: sections/search.php");
    exit;
} else if ($_COOKIE['userEmail'] && $_COOKIE['userType'] == 'admin') {
    header("location: admin/adminHeader.php");
    exit;
}
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
    </body>
    </html>