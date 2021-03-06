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

                <!-- Cookbook Tab Menu -->
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 cookbook-tab-menu">
                    <div class="list-group">
                        <a href="search.php" id="menu-search" class="list-group-item text-center">
                            <h4 class="glyphicon glyphicon-search"></h4>
                            <br/>Search
                        </a>
                        <a href="cookbooks.php" id="menu-cookbooks" class="list-group-item text-center">
                            <h4 class="glyphicon glyphicon-book"></h4>
                            <br/>Cookbooks
                        </a>
                        <a href="bookmarks.php" id="menu-bookmarks" class="list-group-item text-center">
                            <h4 class="glyphicon glyphicon-bookmark"></h4>
                            <br/>Bookmarks
                        </a>
                        <a href="create.php" id="menu-create" class="list-group-item text-center">
                            <h4 class="glyphicon glyphicon-pencil"></h4>
                            <br/>Create
                        </a>
                        <form id="logout-form" action="logout.php">
                            <button type="submit" class="logout-button" name="logout">Logout</button>
                        </form>
                    </div>
                </div>

                <!-- Cookbook Content -->
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 cookbook-tab">
                    <div class="cookbook-tab-content active">