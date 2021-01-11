<?php
session_start();
if($_SESSION['alert'] == 'true')
{
    echo "<script type='text/javascript'>alert('Poprawnie zarejestrowano!'); var i = 0;</script>";
    $_SESSION['alert'] = '';
}
require_once('database_info.php');

$conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS");

$username = $_COOKIE['active_username'];

$result = oci_parse($conn, "SELECT * FROM gracze WHERE nick='".$username."'");
oci_execute($result);

$password = oci_fetch_array($result, OCI_BOTH)['HASLO'];

if (isset($_COOKIE['active_username']) and $_COOKIE['active_password'] == $password)
    header('Location:index.php');
else {
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
        Jeśli nie masz konta <a href='registration_page.php'>Zarejestruj się.</a><br><br>
        <a href='index.php'>Strona główna</a>
    </body>

    </html>

<?php
}
oci_close($conn);
?>