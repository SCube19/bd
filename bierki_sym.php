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
    <button type="button">SYMULUJ BIERKI</button>
    <?php if (isset($_COOKIE['active_username'])) : ?>
        <div class="center"><a href="logout.php">WYLOGUJ</a></div>
        <img class="parowa" src="https://upload.wikimedia.org/wikipedia/commons/f/fb/Hotdog_-_Evan_Swigart.jpg">
    <?php else : ?>
        <div class="center">
            <a href="login_page.php">LOGOWANIE</a><br><br>
            <a href="registration_page.php">REJESTRACJA</a>
        </div>
        <img class="parowa" src="https://s3.amazonaws.com/rapgenius/hotdog.jpg">
    <?php endif; ?>
    <a href='index.php'>Strona główna</a>

</body>

</html>