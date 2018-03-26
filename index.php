<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="style.css">
<script src="tabs.js"></script>

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
          <a href="#" class="list-group-item active text-center">
            <h4 class="glyphicon glyphicon-search"></h4>
            <br/>Search
          </a>
          <a href="#" class="list-group-item text-center">
            <h4 class="glyphicon glyphicon-book"></h4>
            <br/>Cookbooks
          </a>
          <a href="#" class="list-group-item text-center">
            <h4 class="glyphicon glyphicon-bookmark"></h4>
            <br/>Bookmarks
          </a>
          <a href="#" class="list-group-item text-center">
            <h4 class="glyphicon glyphicon-pencil"></h4>
            <br/>Create
          </a>
        </div>
      </div>

      <!-- Cookbook Tabs (Sections) -->
      <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 cookbook-tab">
        <!-- search -->
        <div class="cookbook-tab-content active">
          <?php require "sections/search.php"; ?>
        </div>
        <!-- cookbook -->
        <div class="cookbook-tab-content">
          <?php require "sections/cookbooks.php"; ?>
        </div>
        <!-- bookmark -->
        <div class="cookbook-tab-content">
          <?php require "sections/bookmarks.php"; ?>
        </div>
        <!-- create -->
        <div class="cookbook-tab-content">
          <?php require "sections/create.php"; ?>
        </div>

      </div>
    </div>
  </div>
</body>