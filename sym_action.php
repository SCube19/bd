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
    <div class="center pagetxt" style="background: linear-gradient(180deg, rgba(250,73,24,1) 0%, rgba(255,150,11,1) 100%);">
        <p class="symhead">WYNIK SYMULACJI</p>
        <?php
        session_start();
        require_once('database_info.php');
        require_once('query.php');
        require_once('ratings.php');
        require_once('notation.php');

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

        switch ($game) {
            case 'bierki':
                $values .= piecesN($players);
                break;
            case 'chinczyk':
                $values .= ludoN($players);
                break;
            case 'pilka':
                $values .= soccerN($players);
                break;
            case 'szachy':
                $values .= chessN($players);
                break;
            case 'warcaby':
                $values .= checkersN($players);
                break;
            default:
        }

        query($conn, "INSERT INTO h" . $game . " VALUES (" . $values . ")");
        oci_commit($conn);

        ///////////////////////////update rankingów/////////////////////////////////////////
        $formulas = query($conn, "SELECT * from sposobyObliczania left join formuly on id_formuly=formuly.id where gra = '" . $game . "'");
        $tmp = query($conn, "SELECT pkt_rankingowe from rankingAdvanced r left join sposobyObliczania s on r.id_sposobu = s.id where nick_gracza = '" . $players[0] . "' and gra = '" . $game . "' order by r.id_sposobu");
        $win_rank = $tmp[0]['PKT_RANKINGOWE'][0];

        for ($i = 1; $i < count($players); $i++) {
            $tmp = query($conn, "SELECT pkt_rankingowe from rankingAdvanced r left join sposobyObliczania s on r.id_sposobu = s.id where nick_gracza = '" . $players[$i] . "' and gra = '" . $game . "'order by r.id_sposobu");
            $ranks[] = $tmp[0]['PKT_RANKINGOWE'][0];
        }
        $max_loser = max($ranks);

        $multiplier = 1;
        if ($game == 'bierki' || $game == 'chinczyk')
            $multiplier = 3;

        query($conn, "UPDATE rankingAdvanced SET PKT_RANKINGOWE = " . intval($win_rank + $multiplier * (rating($formulas[0]['FORMULA'][0], $win_rank, $max_loser, ["S", 1]) - $win_rank)) .
            " where nick_gracza = '" . $players[0] . "' and id_sposobu = (SELECT id from sposobyObliczania where gra = '" . $game . "' and id_formuly=0)");
        oci_commit($conn);

        for ($i = 0; $i < count($players) - 1; $i++) {
            query($conn, "UPDATE rankingAdvanced SET PKT_RANKINGOWE = " . intval(rating($formulas[0]['FORMULA'][0], $ranks[$i], $win_rank, ["S", -$i])) .
                " where nick_gracza = '" . $players[$i + 1] . "' and id_sposobu = (SELECT id from sposobyObliczania where gra = '" . $game . "' and id_formuly=0)");
            oci_commit($conn);
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////

        switch ($game) {
            case 'bierki':
            case 'chinczyk':
                $win_rank = $tmp[0]['PKT_RANKINGOWE'][1];
                query($conn, "UPDATE rankingAdvanced SET PKT_RANKINGOWE = " . rating($formulas[0]['FORMULA'][1], $win_rank, $max_loser) .
                    " where nick_gracza = '" . $players[0] . "' and id_sposobu = (SELECT id from sposobyObliczania where gra = '" . $game . "' and id_formuly=" . $formulas[0]['ID_FORMULY'][1] . ")");
                oci_commit($conn);
                break;

            default:
        }
        oci_close($conn);
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        if ($players[0] == $_COOKIE['active_username'])
            echo '<div id="win">ZWYCIĘZCA</div><div id="winner" class="player">' . $players[0] . '</div>';
        else
            echo '<div id="win">ZWYCIĘZCA</div><div id="winner">' . $players[0] . '</div>';
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
            echo '<input type="hidden" name="game" value="' . $game . '">';
            ?>
            <input type="submit" value="POWRÓT" />
        </form>
        <form action="index.php">
            <input type="submit" value="STRONA GŁÓWNA" />
        </form>
    </div>

</body>

</html>