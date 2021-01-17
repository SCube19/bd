<?php
session_start();

$game = $_GET['game'];
if($game == '')
    $game = "szachy";

setcookie('last_page', 'sym.php?game='.$game);

if (!isset($_COOKIE['active_username'])) {
    header('Location:login_page.php');
    exit;
}
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
    <div class="center">
        <div class="pagetxt">
            <?php
                echo '<h1>'. strtoupper($game) . '</h1>';
            ?>

            <form action="logout.php">
                <input type="submit" value="WYLOGUJ" />
            </form>
            <form action="profile.php">
                <input type="submit" value="PROFIL" />
            </form>
            <form method="GET" action="sym_action.php">
            <?php
                echo '<input type="hidden" name="game" value="'.$game.'">';
            ?>
            <input type="submit" value="GRAJ" />
            </form>
            <form action="index.php">
                <input type="submit" value="STRONA GŁÓWNA" />
            </form>
            <form method="GET" action="leaderboards.php">
            <?php
                echo '<input type="hidden" name="game" value="'.$game.'">';
            ?>
            <input type="submit" value="RANKINGI" />
            </form>
        </div>
    </div>

</body>

</html>