<?php
require "header.php";
?>
<script src="../javascript/createCookbook.js"></script>

<h3 class="cookbook-section-header">Cookbooks</h3>
<p>Enter title and description to create your cookbook:</p>
<form method="post" action="cookbooks.php">

    <!-- Cookbook Title -->
    <div class="cookbook-create-section">
        <label>Cookbook Name:</label>
        <input type="text" id="cookbook-create-title" name="cookbookTitle">
    </div>
    <!-- Cookbook Title -->
    <div class="cookbook-create-section">
        <label>Description:</label>
        <input type="text" id="cookbook-create-description" name="cookbookDescription">
    </div>
    <!-- Submit Button -->
    <div class="cookbook-create-section">
        <button type="submit" onClick="submitCookbookForm()" name="uploadCookbook">Create Cookbook</button>
    </div>
</form>
<?php

include_once '../connection.php';

// Connect Oracle...
if ($db_conn) {

    $userEmail = $_COOKIE['userEmail'];

    if (array_key_exists('cookbookSubmit', $_POST)) {
        $cookbookInfo = array(
            ":cookbookTitle" => $_POST['cookbookTitle'],
            ":description" => $_POST['cookbookDescription'],
            ":cid" => uniqid(),
            ":email" => $userEmail
        );
        $alltuples = array(
            $cookbookInfo
        );
        executeBoundSQL("INSERT INTO MANAGEDCOOKBOOK VALUES (:cookbookTitle, :description, :cid, :email)", $alltuples);

        OCICommit($db_conn);
    }

    if ($userEmail != null) {
      $query = "SELECT COOKBOOKTITLE, DESCRIPTION, CID FROM MANAGEDCOOKBOOK WHERE EMAIL = '" . $userEmail . "'";
      $result=  executePlainSQL($query);
      echo "<p>View my cookbooks here: </p>";
      while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<div class='result'>";
          if (array_key_exists("COOKBOOKTITLE", $row) && array_key_exists("CID", $row)) {
              $cid = $row["CID"];
              $title = $row["COOKBOOKTITLE"];
              echo "<a href='cookbookrecipespage.php?cid=" . $cid . "'>" . $title . "</a>";
          } else {
              echo "<p>You have no cookbooks.</p>";
          }
      }
      OCILogoff($db_conn);
  }
} else {
  echo "cannot connect";
  $e = OCI_Error(); // For OCILogon errors pass no handle
  echo htmlentities($e['message']);
}

?>

<?php require "footer.php"; ?>