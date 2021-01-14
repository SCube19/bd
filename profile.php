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
        <?php
        mb_internal_encoding("UTF-8");
        session_start();
        require_once('database_info.php');
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
    $basic = query($conn, "SELECT ilosc_zagranych, ilosc_wygranych, ilosc_remisow, gra FROM rankingBasic WHERE nick_gracza='" . $_COOKIE['active_username'] . "'");
    $advanced = query($conn, "SELECT pkt_rankingowe from rankingAdvanced r LEFT JOIN formuly f ON r.id_formuly = f.id WHERE nick_gracza='" . $_COOKIE['active_username'] . "'");
    oci_close($conn);

    echo'<div class="center2">';
    for ($i = 0; $i < $basic[1]; $i++)
        echo '<a href="leaderboards.php?game=' . $basic[0]['GRA'][$i] . '"><div id="ranking">' . strtoupper($basic[0]['GRA'][$i] . ': ') .
            $advanced[0]['PKT_RANKINGOWE'][$i] .
            '|<span style="color:white;">'.
            $basic[0]['ILOSC_ZAGRANYCH'][$i] .
            '</span>|<span style="color:green;">' .
            $basic[0]['ILOSC_WYGRANYCH'][$i] .
            '</span>|<span style="color:red;">'.
            $basic[0]['ILOSC_REMISOW'][$i] .
            '</div></a>';
    echo'</div>';
    ?>
</div>
</body>

</html>