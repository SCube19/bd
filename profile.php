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

    $result = query($conn, "SELECT ilosc_zagranych, ilosc_wygranych, ilosc_remisow, gra FROM rankingBasic WHERE nick_gracza='".$_COOKIE['active_username']."'");
    for ($i = 0; $i < $result[1]; $i++) 
        echo '<a href="leaderboards.php?game='.$result[0]['GRA'][$i].'">'.strtoupper($result[0]['GRA'][$i].': ').
            'Z: '.
            $result[0]['ILOSC_ZAGRANYCH'][$i].
            '||||| W: '.
            $result[0]['ILOSC_WYGRANYCH'][$i].
            '||||| P: '.
            $result[0]['ILOSC_REMISOW'][$i].
            '</a><br><br>';
    ?>
    <a href='index.php'>Strona główna</a>


</body>

</html>