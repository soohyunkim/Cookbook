<?php
if ($_COOKIE['userType'] !== 'admin') {
    echo "You do not have permission to view this page.";
    exit;
}
?>
<!--TEMPORARY FOR TESTING-->
<html>
<head>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../style.css">
    <script src="../javascript/tabs.js"></script>
</head>

<body>
<div class="cookbook-title">
    <h1>
        Cookbook Database
    </h1>
</div>

<div class="container-fluid">
    <div class="cookbook-tab-container">
<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 cookbook-tab-menu">
    <div class="list-group">
        <a href="manageRecipes.php" id="menu-manage-recipes" class="list-group-item text-center">
            <h4 class="glyphicon glyphicon-list-alt"></h4>
            <br/>Manage Recipes
        </a>
        <a href="manageIngredients.php" id="menu-manage-ingredients" class="list-group-item text-center">
            <h4 class="glyphicon glyphicon-th-list"></h4>
            <br/>Manage Ingredients
        </a>
        <a href="manageUsers.php" id="menu-manage-users" class="list-group-item text-center">
            <h4 class="glyphicon glyphicon-user"></h4>
            <br/>Manage Users
        </a>
        <form id="logout-form" action="../sections/logout.php">
            <button type="submit" class="logout-button" name="logout">Logout</button>
        </form>
    </div>
</div>
</html>

