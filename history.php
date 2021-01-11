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
    <h1>HISTORY.PHP</h1>
    <div class="center">
        <?php if (isset($_COOKIE['active_username'])) : ?>
            <a href="profile.php">PROFIL</a>
            <a href="logout.php">WYLOGUJ</a>
        <?php else : ?>
            <a href="login_page.php">LOGOWANIE</a><br><br>
            <a href="registration_page.php">REJESTRACJA</a>
            <img class="parowa" src="https://s3.amazonaws.com/rapgenius/hotdog.jpg">
        <?php endif; ?>
    </div>

</body>

</html>