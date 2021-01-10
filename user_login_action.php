<?php 
session_start();
require_once('database_info.php');

$conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS");

if (!$conn) {
	echo "oci_connect failed\n";
	$e = oci_error();
	echo $e['message'];
}
else
{
	//dane z formularza do zmiennych
	$input_username = $_POST['username'];
	$input_password = $_POST['password'];
	
	//hasowanie hasla
	$input_password = sha1($input_password);

	//zapytanie
	$result = oci_parse($conn, "SELECT * FROM gracze WHERE nick='".$input_username."'");
	oci_execute($result);

	$numrows = oci_fetch_all($result, $res);

	//zła nazwa użytkownika
	if ($numrows == 0) 
	{
		$_SESSION['login_exists'] = 'false';
		header('Location:login_page.php');
	}
	else 
	{
		//logowanie udane
		$query_password = $res['HASLO'][0];
		if ($input_password == sha1($query_password))
		{
			$_SESSION['auth'] = '';
			$_SESSION['login_exists'] = '';
			setcookie('active_username', $input_username, time() + (3600 * 5));
			setcookie('active_password', $input_password, time() + (3600 * 5));
			header('Location:index.php');
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
?>