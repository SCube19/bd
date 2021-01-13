<?php
session_start();
if ($_SESSION['alert'] == 'true') {
    echo "<script type='text/javascript'>alert('Poprawnie zarejestrowano!');</script>";
    $_SESSION['alert'] = '';
}

if (isset($_COOKIE['active_username']))
    header('Location:index.php');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>Gry.mimuw</title>
    <meta name="description" content="gierki">
    <meta name="author" content="SitePoint">

    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="https://www.mimuw.edu.pl/sites/default/files/mim_mini.png" type="image/png">
</head>

<body>
    <b>Logowanie</b><br><br>
    <form id='player_log' action='user_login_action.php' method='POST'>
        Login:<br>
        <input type='text' name='username' required><br><br>
        Hasło:<br>
        <input type='password' name='password' required><br><br>
        <input type='submit' value="Zaloguj"><br><br>
    </form>
    <?php

    if ($_SESSION['login_exists'] == 'false') {
        echo "<span style='color:red'><b>Niepoprawna nazwa użytkownika.</b></span><br><br>";
        $_SESSION['login_exists'] = '';
    }

    if ($_SESSION['auth'] == 'false') {
        echo "<span style='color:red'><b>Niepoprawne hasło.</b></span><br><br>";
        $_SESSION['auth'] = '';
    }
    ?>
    Jeśli nie masz konta
    <form action="registration_page.php">
        <input type="submit" value="ZAREJESTRUJ SIĘ">
    </form>
    <form action="index.php">
        <input type="submit" value="STRONA GŁÓWNA">
    </form>
</body>

</html>
