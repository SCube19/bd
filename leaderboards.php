<?php
session_start();
$game = htmlspecialchars($_GET['game']);
if ($game == '')
    $game = $_GET['game'];
if ($game == '')
    $game = 'szachy';

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

$formula = query($conn, "SELECT ");
$formulas = query($conn, "SELECT");
$games = query($conn, "SELECT nazwa from gry");
$ranking = query($conn, "SELECT * from rankingAdvanced r join sposobyObliczania s on r.id_sposobu = s.id WHERE gra = '" . $game . "' ORDER BY PKT_RANKINGOWE DESC");

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

        <select class="choose" name=":0">
            <option>:)</option>
            <option>:D</option>
            <option>XD</option>
            <option>;0</option>
            <option>;d</option>
            <option>:p</option>
            <option>siur</option>
            <option>hihi</option>
        </select>

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
            if (isset($_COOKIE['active_username']))
                echo '<form action="logout.php">
            <input type="submit" value="WYLOGUJ" />
        </form>';
            ?>
            <form action="index.php">
                <input type="submit" value="STRONA GŁÓWNA" />
            </form>
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
                            <th class="header glow" style="width:10vw;">ELO</th>
                        </tr>
                    </thead>
                    <tbody>'
        . $table_string .
        '</tbody>
                </table></div>';

    ?>


</body>

</html>