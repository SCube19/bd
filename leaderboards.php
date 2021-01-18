<?php
session_start();
$game = htmlspecialchars($_GET['game']);
if ($game == '')
    $game = $_GET['game'];
if ($game == '')
    $game = 'szachy';

setcookie('last_page', 'leaderboards.php?game=' . $game);
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
    <div class="center">
        <div class="pagetxt">
            <?php
            echo '<h1>RANKING ' . strtoupper($game) . '</h1>';
            ?>
            <?php
            session_start();

            require_once('query.php');
            require_once('database_info.php');
            if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8'))) {
                header("Location: error_page.php");
                exit;
            }

            $player = $_GET['player'];
            if ($player == "")
                $player = $_COOKIE['active_username'];

            $ranking = query($conn, "SELECT * from rankingAdvanced r join sposobyObliczania s on r.id_sposobu = s.id WHERE gra = '" . $game . "' ORDER BY PKT_RANKINGOWE DESC");

            $table_string = "";
            for ($i = 0; $i < $ranking[1]; $i++) {

                $table_string .=
                    '<tr>
                        <td>' . $i . '</td>
                        <td>
                        <a href = "profile.php?player=' . $ranking[0]['NICK_GRACZA'][$i] . '"';
                if ($ranking[0]['NICK_GRACZA'][$i] == $_COOKIE['active_username'])
                    $table_string .= 'class="ranking-user"';
                $table_string .= '>' .
                    $ranking[0]['NICK_GRACZA'][$i]
                    . '</a></td>
                        <td>' . $ranking[0]['PKT_RANKINGOWE'][$i] . '</td>
                    </tr>';
            }


            echo '<table>
                    <thead>
                        <tr>
                            <th>POZYCJA</th>
                            <th>NICK</th>
                            <th>ELO</th>
                        </tr>
                    </thead>
                    <tbody>'
                . $table_string .
                '</tbody>
                </table>';

            ?>

        </div>
    </div>
</body>

</html>