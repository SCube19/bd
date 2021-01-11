<?php
session_start();
setcookie('last_page', 'leaderboards.php', time() + 300);
$game = htmlspecialchars($_GET['game']);
if($game == '')
  $game = 'szachy';
?>

<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">

  <title>Gry.mimuw</title>
  <meta name="description" content="gierki">
  <meta name="author" content="SitePoint">

  <link rel="stylesheet" href="styles.css">
  <link rel="shortcut icon" href="https://www.mimuw.edu.pl/sites/default/files/mim_mini.png" type="image/png">
</head>

<body>

  <h1>Rankingi</h1>
  <?php
    echo "     x    ".$game;
  ?>

  <?php if (isset($_COOKIE['active_username'])) : ?>
    <div class="center"><a href="logout.php">WYLOGUJ</a></div>
  <?php else : ?>
    <div class="center">
      <a href="login_page.php">LOGOWANIE</a><br><br>
      <a href="registration_page.php">REJESTRACJA</a>
    </div>
  <?php endif; ?>
  <a href='index.php'>Strona główna</a>

</body>

</html>