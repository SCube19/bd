<!doctype html>

<html lang="pl">

<head>
    <meta charset="utf-8">

    <title>Gry.mimuw</title>
    <meta name="description" content="gierki">
    <meta name="author" content="kk418331+kj418271">

    <link rel="stylesheet" href="styles.css">
    <?php
    $player = htmlspecialchars($_GET['player']);
    if ($player == "")
        $player = $_COOKIE['active_username'];
    if ($player == "") {
        header("Location: " . $_COOKIE['last_page'] . ".php");
        exit;
    }
    setcookie('last_page', 'profile.php?player=' . $player . '');
    echo '<link rel="stylesheet" href="profile_styles.php?player=' . $player . '">';
    ?>
    <link rel="shortcut icon" href="https://www.mimuw.edu.pl/sites/default/files/mim_mini.png" type="image/png">

</head>

<body>
    <div class="up">
        <?php
        mb_internal_encoding("UTF-8");
        session_start();

        require_once('query.php');
        require_once('database_info.php');
        if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8'))) {
            header("Location: error_page.php");
            exit;
        }

        $result = query($conn, "SELECT * FROM gracze WHERE nick='" . $player . "'");

        if($result[0]['TYP_GRACZA'][0] == 'usuniety')
            header("Location: error_page.php");
        if ($result[0]['TYP_GRACZA'][0] == 'admin' && $player == $_COOKIE['active_username'])
            echo '<a href="admin_panel.php">';
        if ($result[0]['TYP_GRACZA'][0] != 'uzytkownik')
            echo '<div class="left">' . $result[0]['TYP_GRACZA'][0] . '</div>';
        if ($result[0]['TYP_GRACZA'][0] == 'admin' && $player == $_COOKIE['active_username'])
            echo '</a>';

        if ($player == $_COOKIE['active_username'])
            echo '<div class="del">
            <a href="del.php">
                USUN KONTO
            </a>
                </div>';

        echo '<div id="nick">' . $player . '</div>';
        ?>


        <div class="right">
            <?php
            if ($_COOKIE['active_username'] == "")
                echo '<form action="login_page.php">
                <input type="submit" value="ZALOGUJ" />
                 </form>';
            echo '<form action="index.php">
            <input type="submit" value="STRONA GŁÓWNA" />
         </form>';
            if ($player == $_COOKIE['active_username'])
                echo '<form action="logout.php">
                <input type="submit" value="WYLOGUJ" />
            </form>';
            if($player != $_COOKIE['active_username'])
                echo '<form action="leaderboards.php">
                <input type="submit" value="POWRÓT DO RANKINGÓW" />
            </form>';
            ?>
        </div>
    </div>

    <div class="pagetxt">
        <?php

        $ranks = query($conn, "SELECT gra from rankingBasic natural join (rankingAdvanced r left join (sposobyObliczania s left join formuly f on f.id = s.id_formuly) on r.id_sposobu = s.id) where nick_gracza = '" . $player . "' and id_formuly=0 order by gra");
        oci_close($conn);

        echo '<div class="center2">';
        for ($i = 0; $i < $ranks[1]; $i++)
            echo '<div><div class="ranking"><a href="leaderboards.php?game=' . $ranks[0]['GRA'][$i] . '"><div id="rank' . ($i + 1) . '"></div>
        </a></div>
        <form method="GET" class="center2 history" action="history.php">
        <input type="hidden" name="game" value="' . $ranks[0]['GRA'][$i] . '">
        <input type="hidden" name="player" value="' . $player . '">
        <input class="history" type="submit" value="HISTORIA ROZGRYWEK" />
        </form></div>
            ';

        ?>
    </div>


</body>

</html>