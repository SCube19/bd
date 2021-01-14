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
    <div class="bod">
        <div class="up">
            
                <img class="left" src="https://www.mimuw.edu.pl/sites/all/themes/mimuwtheme/images/MIM_logo_sygnet_pl.png">
           
            <div class="right">
                <?php if (isset($_COOKIE['active_username'])) : ?>
                    <form action="profile.php">
                        <input type="submit" value="PROFIL" />
                    </form>
                    <form action="logout.php">
                        <input type="submit" value="WYLOGUJ" />
                    </form>
                <?php else : ?>
                    <form action="login_page.php">
                        <input type="submit" value="ZALOGUJ" />
                    </form>
                    <form action="registration_page.php">
                        <input type="submit" value="ZAREJESTRUJ" />
                    </form>
                <?php endif; ?>
                <form action="leaderboards.php">
                    <input type="submit" value="RANKINGI" />
                </form>
            </div>
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
            "https://www.xmple.com/wallpaper/checkered-black-white-squares-1080x1920-c2-fff5ee-000000-l-60-a-70-f-2.svg"
        );

        echo '<div class="parent">';
        for ($i = 0; $i < $result[1]; $i++)
            echo '
                    <div class="box">
                    <div class="container">
                    <a href="' . $result[0]['NAZWA'][$i] . '.php">
                    <img class="box" src="' . $imgs[$i] . '">
                    <div class="middle"><div class="boxtext">' . strtoupper($result[0]['NAZWA'][$i]) . '</div></div>
                    </a>
                    </div>
                </div>';

        // Placeholder imgs
        for ($i = 0; $i < 4; $i++)
            echo '<div class="box">
                        <div class="container">
                        <a href="#">
                        <img class="box" src="' . $imgs[3] . '">
                        <div class="middle"><div class="boxtext">PLACEHOLDER</div></div>
                        </a>
                        </div>
                    </div>';
        echo '</div>';

        oci_close($conn);
        ?>
    </div>
</body>

</html>