<?php
$game = htmlspecialchars($_GET['game']);
if ($game == '')
    $game = 'szachy';
setcookie('last_page', 'game_panel.php?game=' . $game . '');
?>

<!doctype html>

<html lang="pl">

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
    <?php else : ?>
        <form action="login_page.php">
            <input type="submit" value="ZALOGUJ" />
        </form>
        <form action="registration_page.php">
            <input type="submit" value="ZAREJESTRUJ" />
        </form>
    <?php endif;
    echo '<form action="leaderboards.php">
        <input type="submit" value="RANKINGI" />
    </form>';

    if (isset($_COOKIE['active_username']))
        echo '<form action="logout.php">
            <input type="submit" value="WYLOGUJ" />
        </form>';
    ?>
</div>
</div>
    <div class="center gamebox">
    <div class="divider"></div>
    <div class="desc">
        <?php
        mb_internal_encoding("UTF-8");
            session_start();

            require_once('query.php');
            require_once('database_info.php');
            if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8'))) {
                header("Location: error_page.php");
                exit;
            }
            $desc = query($conn, "SELECT opis from gry where nazwa='".$game."'");
            oci_close($conn);
            switch($game)
            {
                case 'bierki':
                    $img = "https://upload.wikimedia.org/wikipedia/commons/5/53/Bierki.JPG";
                    break;
                case 'chinczyk':
                    $img = "https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Menschenaergern.svg/1200px-Menschenaergern.svg.png";
                    break;
                case 'pilka':
                    $img = "https://www.kurnik.pl/pilka/img/i0.gif";
                    break;
                case 'szachy':
                    $img = "https://www.chess.com/bundles/web/images/offline-play/standardboard.6a504885.png";
                    break;
                case 'warcaby':
                    $img = "https://www.kurnik.pl/warcaby/img/img1.gif";
                    break;
                default:
                    $img = "https://lh3.googleusercontent.com/proxy/Bl0zKY9SYpsYgZZGww-xBQULWN6vhbA3uAVaEoFlJb7VE_gOFAdjDahGcJAzniIBNkvW_vyiYXY2zUgFyHvd8EyovtuuQ64";
            }

            echo '<img class="desc" src='.$img.'>';
            echo '<div class="desctxt">'.utf8_encode($desc[0]['OPIS'][0]).'</div>';
        ?>
        </div>
        <div class="gamebuttons">
            <?php
            echo '<h2>' . strtoupper($game) . '</h2>';
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
    </div>

</body>

</html>