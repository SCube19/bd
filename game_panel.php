<?php
$game = htmlspecialchars($_GET['game']);
if ($game == '')
    $game = 'szachy';
setcookie('last_page', 'game_panel.php?game=' . $game . '');
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
    <div class="up">

        <img class="left" src="https://www.mimuw.edu.pl/sites/all/themes/mimuwtheme/images/MIM_logo_sygnet_pl.png">

        <div id="MyClockDisplay" class="clock" onload="showTime()"></div>
        <script src="clock.js">
        </script>

        <div class="right">
            <?php if (isset($_COOKIE['active_username'])) : ?>
                <form action="profile.php">
                    <input type="submit" value="PROFIL" />
                </form>
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
            <form action="index.php">
                <input type="submit" value="STRONA GŁÓWNA" />
            </form>
        </div>
    </div>
    <div class="center2 pagetxt gamebox">
            <?php
            echo '<h1>' . strtoupper($game) . '</h1>';
            ?>
            <form method="GET" action="sym_action.php">
                <?php
                echo '<input type="hidden" name="game" value="' . $game . '">';
                ?>
                <input type="submit" value="GRAJ" />
            </form>

            <form method="GET" action="leaderboards.php">
                <?php
                echo '<input type="hidden" name="game" value="' . $game . '">';
                ?>
                <input type="submit" value="RANKINGI" />
            </form>
    </div>

</body>

</html>