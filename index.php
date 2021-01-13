<?php
setcookie('last_page', 'index.php', time() + 300);
?>

<!doctype html>
<html lang="pl">

<head>
    <meta charset="utf-8">

    <title>Gry.mimuw</title>
    <meta name="description" content="gierki">
    <meta name="author" content="SitePoint">

    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="https://www.mimuw.edu.pl/sites/default/files/mim_mini.png" type="image/png">
</head>

<body>

    <div class="center">
        <?php if (isset($_COOKIE['active_username'])) : ?>
            <a href="profile.php">PROFIL</a><br><br>
            <a href="logout.php">WYLOGUJ</a><br><br>
        <?php else : ?>
            <a href="login_page.php">LOGOWANIE</a><br><br>
            <a href="registration_page.php">REJESTRACJA</a><br><br>
        <?php endif;?>
        <a href="leaderboards.php">Rankingi</a><br><br>
    </div>
    <?php
        require_once('database_info.php');
        require_once('query.php');
        if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8'))) 
            header("Location: error_page.php");
        
        $result = query($conn, "SELECT nazwa FROM gry");
        $imgs = array(
        "straws.jpg",
        "https://s2.best-wallpaper.net/wallpaper/iphone/1911/Red-and-blue-pawn_iphone_1080x1920.jpg",
        "https://images.wallpaperscraft.com/image/soccer_ball_nike_grass_113053_1080x1920.jpg",
        "https://mfiles.alphacoders.com/166/166285.jpg",
        "https://www.xmple.com/wallpaper/checkered-black-white-squares-1080x1920-c2-fff5ee-000000-l-60-a-70-f-2.svg");
        
        echo '<div class="parent">';
        for ($i = 0; $i < $result[1]; $i++)
            echo '<div class="box"><p class="cen">'.strtoupper($result[0]['NAZWA'][$i]).'</p>
                    <a href="' . $result[0]['NAZWA'][$i] .'.php">
                    <img class="box" src="'.$imgs[$i].'">
                    </a>
                </div>';
        echo '</div>';

        oci_close($conn);
        ?>
        

</body>
</html>

