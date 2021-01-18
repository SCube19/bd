<?php
if (!isset($_COOKIE['active_username'])) {
    header('Location:login_page.php');
    exit;
}

$game = $_GET['game'];
if ($game == '')
    $game = "szachy";

$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

if ($pageWasRefreshed) {
    header('Location:game_panel.php?game=' . $game);
    exit;
}

setcookie('last_page', 'sym_action.php?game=' . $game);
?>

<!DOCTYPE HTML>
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
    <div class="center pagetxt" style="background: linear-gradient(180deg, rgba(250,73,24,1) 0%, rgba(255,150,11,1) 100%);">
            <p class="symhead">WYNIK SYMULACJI</p>
            <?php
            session_start();
            require_once('database_info.php');
            require_once('query.php');

            if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS"))) {
                header('Location:error_page.php');
                exit;
            }

            $bot_query = query($conn, "SELECT nick FROM gracze WHERE typ_gracza='bot'");
            $player_query = query($conn, "SELECT min_graczy, max_graczy FROM gry WHERE nazwa='" . $game . "'");
            $min_players = $player_query[0]['MIN_GRACZY'][0];
            $max_players = $player_query[0]['MAX_GRACZY'][0];

            if ($bot_query[1] == 0) {
                echo '<script type=text/javascript> noBotsAvailable(); </script>';
                header('Location:game_panel.php?game=' . $game);
                exit;
            }

            $opponent_count = rand($min_players, $max_players) - 1;

            ///////////////////////////////////////// symulacja

            //shuffle calego arraya botow moze byc wolne
            function uniqueRandom($min, $max, $count)
            {
                $res = range($min, $max);
                shuffle($res);
                return array_slice($res, 0, $count);
            }

            $bot_rownums = uniqueRandom(0, $bot_query[1] - 1, $opponent_count);
            $players = array();

            for ($i = 0; $i < $opponent_count; $i++) {
                $players[] = $bot_query[0]['NICK'][$bot_rownums[$i]];
            }
            $players[] = $_COOKIE['active_username'];

            shuffle($players);

            //inserty
            $new_id = query($conn, "SELECT nvl(max(id), 0) + 1 x FROM h" . $game);
            $values = "" . $new_id[0]['X'][0];

            for ($i = 0; $i < $opponent_count + 1; $i++) {
                $values .= ",'";
                $values .= $players[$i];
                $values .= "'";
            }
            for ($i = 0; $i < $max_players - $opponent_count - 1; $i++)
                $values .= ",NULL";

            query($conn, "INSERT INTO h" . $game . " VALUES (" . $values . ")");
            oci_commit($conn);

            if ($players[0] == $_COOKIE['active_username'])
                echo '<div id="win">ZWYCIĘZCA</div><div id="winner" class="player">1. ' . $players[0] . '</div>';
            else
                echo '<div id="win">ZWYCIĘZCA</div><div id="winner">1. ' . $players[0] . '</div>';
            echo '<div class="symbox">';
            
            for ($i = 1; $i < $opponent_count + 1; $i++) {
                if ($players[$i] == $_COOKIE['active_username'])
                    echo '<div class="loser player">' . ($i + 1) . '. ' . $players[$i] . '</div>';
                else
                    echo '<div class="loser">' . ($i + 1) . '. ' . $players[$i] . '</div>';
            }
            echo '</div>';
            oci_close($conn);
            ?>
            <script>
                function noBotsAvailable() {
                    alert("Aktualnie nie ma dostępnych botów na stronie, przepraszamy!");
                }
            </script>
            <form method="GET" action="sym_action.php">
                <?php
                echo '<input type="hidden" name="game" value="' . $game . '">';
                ?>
                <input class="playagain" type="submit" value="ZAGRAJ PONOWNIE" />
            </form>

            <form method="GET" action="game_panel.php">
                <?php
                echo '<input type="hidden" name="game" value="'.$game.'">';
                ?>
                <input type="submit" value="POWRÓT" />
            </form>
            <form action="index.php">
                <input type="submit" value="STRONA GŁÓWNA" />
            </form>
    </div>

</body>

</html>