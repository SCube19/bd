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
    <div class="center"><a href="logout.php">WYLOGUJ</a></div>

    <?php
    mb_internal_encoding("UTF-8");
    session_start();
    require_once('database_info.php');
    require_once('query.php');

    require_once('database_info.php');
        if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8'))) 
            header("Location: error_page.php");

    $result = query($conn, "SELECT typ_gracza FROM gracze WHERE nick='".$_COOKIE['active_username'] . "'");

    echo '<p>' . "Nick : " . $_COOKIE['active_username'] . '</p>';
    echo '<p>' . "Typ Gracza : " . $result[0]['TYP_GRACZA'][0] . '</p>';

    $basic = query($conn, "SELECT ilosc_zagranych, ilosc_wygranych, ilosc_remisow, gra FROM rankingBasic WHERE nick_gracza='".$_COOKIE['active_username']."'");
    $advanced = query($conn, "SELECT pkt_rankingowe from rankingAdvanced r LEFT JOIN formuly f ON r.id_formuly = f.id WHERE nick_gracza='".$_COOKIE['active_username']."'");
    oci_close($conn);

    for ($i = 0; $i < $basic[1]; $i++) 
        echo '<a href="leaderboards.php?game='.$basic[0]['GRA'][$i].'">'.strtoupper($basic[0]['GRA'][$i].': ').
            'Z: '.
            $basic[0]['ILOSC_ZAGRANYCH'][$i].
            '||||| W: '.
            $basic[0]['ILOSC_WYGRANYCH'][$i].
            '||||| P: '.
            $basic[0]['ILOSC_REMISOW'][$i].
            '||||| RANKING: '.
            $advanced[0]['PKT_RANKINGOWE'][$i].
            '</a><br><br>';
    ?>
    <a href='index.php'>Strona główna</a>

<!--  -->
    TEST WYGRANEJ W SZACHY GRACZA z graczem 1200:
    <p id="rating"></p>
    <?php
        require_once('database_info.php');
        require_once('query.php');
        if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8')))
            header("Location: error_page.php");

        $result = query($conn, "SELECT formula, pkt_rankingowe from rankingAdvanced r left join formuly f on r.id_formuly = f.id where nick_gracza='".$_COOKIE['active_username']."' AND gra='szachy'");
        for ($i = 0; $i < $result[1]; $i++)
            echo $result[0]['PKT_RANKINGOWE'][$i] . ' ' . $result[0]['FORMULA'][$i] . '\n';
        
        echo '<script type="text/JavaScript" src="ratings.js">
            </script>';

        echo '<script type="text/JavaScript">
            document.getElementById("rating").innerHTML = String(rating("'.$result[0]['FORMULA'][0].'",'. $result[0]['PKT_RANKINGOWE'][0].', 1200, ["S", 1]));
            </script>';
              
        oci_close($conn);
        ?>

</body>

</html>