<?php
session_start();
$game = htmlspecialchars($_GET['game']);
if ($game == '')
    $game = $_GET['game'];
if ($game == '')
    $game = $_COOKIE['last_game'];
if ($game == '')
    $game = 'szachy';

setcookie('last_game', $game);
setcookie('last_page', 'leaderboards.php?game=' . $game);

require_once('query.php');
require_once('database_info.php');
if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8'))) {
    header("Location: error_page.php");
    exit;
}

$player = $_GET['player'];
if ($player == "")
    $player = $_COOKIE['active_username'];

$formula = $_GET['formula'];
if ($formula == '')
    $formula = query($conn, "SELECT nazwa from formuly where id=0")[0]['NAZWA'][0];


$formulas = query($conn, "SELECT nazwa from formuly left join sposobyObliczania s on formuly.id = s.id_formuly where gra = '" . $game . "'");
$games = query($conn, "SELECT nazwa from gry");
$ranking = query($conn, "SELECT nick_gracza, pkt_rankingowe, nazwa
from rankingAdvanced r left join sposobyObliczania s left join formuly f on s.id_formuly = f.id on r.id_sposobu = s.id
WHERE gra = '" . $game . "' and nazwa='" . $formula . "' ORDER BY PKT_RANKINGOWE DESC");

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

        <img class="left" src="https://www.mimuw.edu.pl/sites/all/themes/mimuwtheme/images/MIM_logo_sygnet_pl.png">

        <?php
        echo
        '<div class="choose">
            <div style="float:left;" class="fullform">
            <form action="leaderboards.php" method="get">
            <select name="game">
            <option value="" disabled selected>Wybierz gre</option>';
        for ($i = 0; $i < $games[1]; $i++)
            echo '<option value="' . $games[0]['NAZWA'][$i] . '">' . strtoupper($games[0]['NAZWA'][$i]) . '</option>';
        echo '</select>
            <input class="rnk" type="submit" value="WYBIERZ">
            </form></div>';

        echo '<div style="float:right;"class="fullform"><form action="leaderboards.php" method="get">
            <select name="formula">
            <option value="" disabled selected>Wybierz rodzaj</option>';
        for ($i = 0; $i < $formulas[1]; $i++)
            echo '<option value=' . $formulas[0]['NAZWA'][$i] . '>' . strtoupper($formulas[0]['NAZWA'][$i]) . '</option>';
        echo '</select>
            <input class="rnk" type="submit" value="WYBIERZ">
            </form></div></div>';
        ?>

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
    <?php
    echo '<h3 class="glow">RANKING ' . strtoupper($game) . '</h3>';
    ?>
    <?php

    $table_string = "";
    for ($i = 0; $i < $ranking[1]; $i++) {
        if ($ranking[0]['NICK_GRACZA'][$i] == $_COOKIE['active_username'])
            $color = "red";
        else if ($i % 2 == 0)
            $color = "yellow";
        else
            $color = "orange";

        $table_string .=
            '<tr style="background-color:' . $color . '">
                <td class="idtd">' . ($i + 1) . '</td>
                <td class="playertd">
                <a href = "profile.php?player=' . $ranking[0]['NICK_GRACZA'][$i] . '"';
        if ($ranking[0]['NICK_GRACZA'][$i] == $_COOKIE['active_username'])
            $table_string .= 'class="ranking-user"';
        $table_string .= '>' .
            $ranking[0]['NICK_GRACZA'][$i]
            . '</a></td>
                 <td class="ranktd">' . $ranking[0]['PKT_RANKINGOWE'][$i] . '</td>
            </tr>';
    }

    echo '<div class="center2"><table>
                    <thead>
                        <tr>
                            <th class="header glow" style="width:4.5vw;">POZ.</th>
                            <th class="header glow" style="width:40vw;">NICK</th>
                            <th class="header glow" style="width:10vw;">' . strtoupper($formula) . '</th>
                        </tr>
                    </thead>
                    <tbody>'
        . $table_string .
        '</tbody>
                </table></div>';

    ?>

</body>

</html>