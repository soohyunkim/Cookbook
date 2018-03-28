<?php
if ($_COOKIE['userType'] !== 'admin') {
    echo "You do not have permission to view this page.";
    exit;
}
?>
<html>
<form id="logout-form" action="../sections/logout.php">
    <button type="submit" class="logout-button" name="logout">Logout</button>
</form>
</html>
