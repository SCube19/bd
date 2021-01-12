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
        <?php endif;

        require_once('database_info.php');
        require_once('query.php');
        if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8'))) 
            header("Location: error_page.php");
        
        $result = query($conn, "SELECT nazwa FROM gry");
        for ($i = 0; $i < $result[1]; $i++)
            echo '<a href="' . $result[0]['NAZWA'][$i] . '.php">' . strtoupper($result[0]['NAZWA'][$i]) . '</a><br><br>';

        oci_close($conn);
        ?>
        <a href="leaderboards.php">Rankingi</a><br><br>
    </div>
</body>
</html>

