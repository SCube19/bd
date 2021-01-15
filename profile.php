<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">

    <title>Gry.mimuw</title>
    <meta name="description" content="gierki">
    <meta name="author" content="SitePoint">

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="profile_styles.php">
    <link rel="shortcut icon" href="https://www.mimuw.edu.pl/sites/default/files/mim_mini.png" type="image/png">

</head>

<body>
    <div class="up">
        <?php
        mb_internal_encoding("UTF-8");
        session_start();

        require_once('query.php');
        require_once('database_info.php');
        if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8')))
            header("Location: error_page.php");

        $result = query($conn, "SELECT typ_gracza FROM gracze WHERE nick='" . $_COOKIE['active_username'] . "'");

        if ($result[0]['TYP_GRACZA'][0] != 'uzytkownik')
            echo '<div class="left">' . $result[0]['TYP_GRACZA'][0] . '</div>';
        echo '<div id="nick">' . $_COOKIE['active_username'] . '</div>';
        ?>


        <div class="right">
            <form action="logout.php">
                <input type="submit" value="WYLOGUJ" />
            </form>
            <form action="index.php">
                <input type="submit" value="STRONA GŁÓWNA" />
            </form>
        </div>
    </div>

    <div class="pagetxt">
        <?php

        $ranks = query($conn, "SELECT * from rankingBasic natural join (rankingAdvanced r left join formuly f on r.id_formuly = f.id) where nick_gracza = '" . $_COOKIE['active_username'] . "' order by gra");

        echo '<div class="center2">';
        for ($i = 0; $i < $ranks[1]; $i++)
            echo '<div><div class="ranking"><a href="leaderboards.php?game=' . $ranks[0]['GRA'][$i] . '"><div id="rank' . ($i + 1) . '"></div>
        </a></div>
        <form class="center2 history" action="history.php?game=' . $ranks[0]['GRA'][$i] . '">
        <input class="history" type="submit" value="HISTORIA ROZGRYWEK" />
        </form></div>
            ';

        for ($i = 0; $i < 4; $i++)
            echo '<div><div class="ranking"><a href="leaderboards.php"><div id="rank4"></div>
        </a></div>
        <form class="center2 history" action="history.php">
        <input class="history" type="submit" value="HISTORIA ROZGRYWEK" />
        </form></div>
            ';
        echo '</div>';
        ?>
    </div>


</body>

</html>