<?php
$game = htmlspecialchars($_GET['game']);
if ($game == '')
    $game = 'szachy';
setcookie('last_page', 'game_panel.php?game='.$game.'');
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
<div class="bod">
    <div class="center">
        <div class="pagetxt">
            <?php
            echo '<h1>'. strtoupper($game) . '</h1>';
            ?>
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
            <?php endif; ?>
            <?php
            echo '<form method="GET" action="sym.php">
            <input type="hidden" name="game" value="'.$game.'">
            <input type="submit" value="ZAGREJ SE" />
            </form>';
            ?>

            <form action="index.php">
                <input type="submit" value="STRONA GŁÓWNA" />
            </form>
            <?php
            echo '<form method="GET" action="leaderboards.php">
            <input type="hidden" name="game" value="'.$game.'">
            <input type="submit" value="RANKINGI" />
            </form>';
            ?>
        </div>
    </div>
    </div>
</body>

</html>