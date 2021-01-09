<?php session_start();
require_once('database_info.php');

$conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS");

if (!$conn) {
	echo "oci_connect failed\n";
	$e = oci_error();
	echo $e['message'];
	  }
else
{
	  
$username = $_POST['username'];
$password = $_POST['password'];
$password = sha1($password);

$result = oci_parse($conn, "SELECT * FROM gracze WHERE nick = '$username';");
$_SESSION['test'] = oci_parse($conn, "SELECT table_name FROM user_tables");
oci_execute($result);
oci_execute($_SESSION['test']);

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
}
oci_close($conn);
