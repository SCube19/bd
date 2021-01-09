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
        <title>Strona rejestracji</title>
    </head>

    <body>
        <b>Rejestracja</b><br><br>
		<form id='user_reg' action='user_registration_action.php' method='POST'>
			Nick:<br>
			<input type='text' name='username' required><br><br>
			Hasło:<br>
			<input type='password' name='password' required><br><br>
			Powtórz hasło:<br>
			<input type='password' name='password_repeat' required><br><br>
			<input type='submit' value="Zarejestruj"><br><br>
		</form>
		<?php
			//nazwa użytkownika zajęta
			if ($_SESSION['available'] == 'false') {
				echo "<span style='color:red'><b>Podana nazwa użytkownika jest zajęta.</b></span><br><br>";
				$_SESSION['available'] = '';
			}

			//hasła różnią się
			if ($_SESSION['password_match'] == 'false') {
				echo "<span style='color:red'><b>Podane hasła różnią się od siebie.</b></span><br><br>";
				$_SESSION['password_match'] = '';
			}

		?>
			_____________________<br><br>
			<a href='login_page.php'>Logowanie</a><br><br>
			<a href='index.html'>Strona główna</a>
    </body>

    </html>

<?php
}
oci_close($conn);
?>