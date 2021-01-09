<?php session_start();
require_once('database_info.php');

$conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS");

$username = $_COOKIE['player_username'];
$result = oci_parse($conn, "SELECT * FROM gracze WHERE nick = '$username';");
$password = oci_fetch_array($result, OCI_BOTH)['haslo'];

if (isset($_COOKIE['player_username']) and $_COOKIE['player_pass'] == $password)
    header('Location:index.html');
else {
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset='utf-8'>
        <title>Strona logowania</title>
    </head>

    <body>
        <b>Logowanie</b><br><br>
        <form id='player_log' action='user_login_action.php' method='POST'>
            Login:<br>
            <input type='text' name='username'><br><br>
            Hasło:<br>
            <input type='password' name='password'><br><br>
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
        Jeśli nie masz konta <a href='user_registration.php'>Zarejestruj się.</a><br><br>
        <a href='index.html'>Strona główna</a>
    </body>

    </html>

<?php
}
oci_close($conn);
?>