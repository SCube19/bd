<?php
setcookie('last_page', 'bierki.php');
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

    <h1>Bierki</h1>
    <div class="center">
        <?php if (isset($_COOKIE['active_username'])) : ?>
            <a href="logout.php">WYLOGUJ</a><br><br>
        <?php else : ?>
            <a href="login_page.php">LOGOWANIE</a><br><br>
            <a href="registration_page.php">REJESTRACJA</a><br><br>
        <?php endif; ?>
        <a href="bierki_sym.php">zagrej se</a><br><br>
        <a href='index.php'>Strona główna</a><br><br>
        <a href='leaderboards.php'>Rankingi</a><br><br>
    </div>

</body>

</html>