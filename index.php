<?php

$active_user = False;
if (isset($_COOKIE['active_username']))
  $active_user = True;

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The HTML5 Herald</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">

  <link rel="stylesheet" href="css/styles.css?v=1.0">

</head>

<body>
  
  <?php //if ($active_user) : ?>
    <!-- <a href = "logout.php">WYLOGUJ</a><br><br> -->
  <?php //else : ?>
    <a href = "login_page.php">LOGOWANIE</a><br><br>
  <?php //endif; ?>

  <a href = "registration_page.php">REJESTRACJA</a>
  
</body>
</html>
