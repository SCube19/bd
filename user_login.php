<?php session_start();
require_once('database_info.php');

$conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS");

$username = $_POST['Username'];
$password = $_POST['Password'];
$password = sha1($password);

$result = oci_parse($conn, "SELECT * FROM gracze WHERE nick = '$username';");
oci_execute($result, OCI_NO_AUTO_COMMIT);

$count = oci_num_rows($result);

//zła nazwa użytkownika
if ($count == 0) 
{
	$_SESSION['login_exists'] = 'false';
	header('Location:login_page.php');
} 
else 
{

	//logowanie udane
	if ($password == oci_fetch_array($result, OCI_BOTH)['haslo']) 
	{
		$_SESSION['auth'] = '';
		$_SESSION['login_exists'] = '';
		setcookie('player_username', $username, time() + (3600 * 5));
		setcookie('player_pass', $password, time() + (3600 * 5));
		header('Location:index.html');
	}
	//złe hasło
	else 
	{
		$_SESSION['auth'] = 'false';
		header('Location:login_page.php');
	}
}

oci_close($conn);
