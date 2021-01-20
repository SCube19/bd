<?php
session_start();
$game = htmlspecialchars($_GET['game']);
if ($game == '')
    $game = $_GET['game'];
if ($game == '')
    $game = 'szachy';

$player = htmlspecialchars($_GET['player']);
if ($player == "")
    $player = $_COOKIE['active_username'];
if ($player == "") {
    header("Location: " . $_COOKIE['last_page'] . ".php");
    exit;
}

setcookie('last_page', 'leaderboards.php?game=' . $game . '');
?>

<!doctype html>

<html lang="pl">

<head>
    <meta charset="utf-8">

    <title>Gry.mimuw</title>
    <meta name="description" content="gierki">
    <meta name="author" content="kk418331+kj418271">

    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="https://www.mimuw.edu.pl/sites/default/files/mim_mini.png" type="image/png">
</head>

<body>

    <div class="up">
        <?php
        echo '<div class="left" style="font-size: 5vw">' . $game . '</div>';
        ?>

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
            echo '<form action="index.php">
        <input type="submit" value="STRONA GŁÓWNA" />
    </form>';

            if (isset($_COOKIE['active_username']))
                echo '<form action="logout.php">
            <input type="submit" value="WYLOGUJ" />
        </form>';
            ?>

        </div>
    </div>


    <div class="center2 pagetxt">
        <?php
        session_start();

        require_once('query.php');
        require_once('database_info.php');
        if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8'))) {
            header("Location: error_page.php");
            exit;
        }

        $histories = query($conn, "SELECT * from h" . $game . " h left join (select id, gra, nick_gracza, to_char(data, 'YYYY/MM/DD HH24:MI:SS') data from rozgrywki ) r on r.id = h.id and r.gra = '" . $game . "' where nick_gracza = '" . $player . "' order by data desc");
        $places = count($histories[0]) - 3;
        echo '<div class="ranking">';
        for ($i = 0; $i < $histories[1]; $i++) {
            echo '<a href="history_detailed.php?id=' . $histories[0]['ID'][$i] . '&game=' . $game . '"><div class="gameHis"';
            if ($histories[0]['MIEJSCE_1'][$i] == $player)
                echo 'style="background-color:#149f44;"><div class="hisHead">WYGRANA';
            else
                echo 'style="background-color:#c41212;"><div class="hisHead">PRZEGRANA';

            echo '<div class="date">' . $histories[0]['DATA'][$i] . '</div></div>';
            for ($j = 0; $j < $places; $j++) {
                if ($histories[0]['MIEJSCE_' . ($j + 1) . ''][$i] != '') {
                    echo '<div class="players">';
                    if ($histories[0]['MIEJSCE_' . ($j + 1) . ''][$i] == $player)
                        echo '<div id="usr">';
                    echo ($j + 1) . '. ' . $histories[0]['MIEJSCE_' . ($j + 1) . ''][$i] . '</div>';
                    if ($histories[0]['MIEJSCE_' . ($j + 1) . ''][$i] == $player)
                        echo '</div>';
                }
            }
            echo '</div></a>';
        }
        echo '</div>'
        ?>
    </div>

</body>

</html>