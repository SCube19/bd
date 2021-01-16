<?php
session_start();

$game = $_GET['game'];
if($game == '')
    $game = "szachy";

setcookie('last_page', 'sym.php?game='.$game);

if (!isset($_COOKIE['active_username']))
{
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
        <?php
            echo '<a href="sym_action.php?game='.$game.'">SYMULUJ '.strtoupper($game).'</a>';
        ?>
        <br>
        <a href="logout.php">WYLOGUJ</a><br>
        <a href='index.php'>Strona główna</a><br>
    </div>

</body>

</html>