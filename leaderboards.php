<?php
session_start();
$game = htmlspecialchars($_GET['game']);
if($game == '')
$game = $_GET['game'];
if($game == '')
  $game = 'szachy';

setcookie('last_page', 'leaderboards.php?game='.$game.'');
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

<div class="center">
<div class="pagetxt">
    <h1>Bierki</h1>
    <?php if (isset($_COOKIE['active_username'])) : ?>
            <form action="logout.php">
            <input type="submit" value="WYLOGUJ" />
            </form>
        <?php else : ?>
            <form action="login_page.php">
            <input type="submit" value="ZALOGUJ" />
            </form>
            <form action="registration_page.php">
            <input type="submit" value="ZAREJESTRUJ" />
            </form>
    <?php endif;?>

  <form action="index.php">
    <input type="submit" value="STRONA GŁÓWNA" />
  </form>
        </div></div>
</body>

</html>