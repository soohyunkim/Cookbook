<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <script src="javascript/login.js"></script>
    </head>

    <body>
        <h3 class="login-header">Cookbook Database Login</h3>
        <form id="login-form" method="post" action="sections/search.php">

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
            <button type="button" onClick="loginClicked()" class="login-button">Login</button>

        </form>
    </body>
</html>