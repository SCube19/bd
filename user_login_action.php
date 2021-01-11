<?php
session_start();
require_once('database_info.php');
require_once('query.php');

$conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS");

if (!$conn) {
	echo "oci_connect failed\n";
	$e = oci_error();
	echo $e['message'];
} else {
	//dane z formularza do zmiennych
	$input_username = $_POST['username'];
	$input_password = $_POST['password'];

	//hasowanie hasla
	$input_password = sha1($input_password);

	//zapytanie
	$result = query($conn, "SELECT * FROM gracze WHERE nick='".$input_username."'");

	//zła nazwa użytkownika
	if ($result[1] == 0) {
		$_SESSION['login_exists'] = 'false';
		header('Location:login_page.php');
	} else {
		//logowanie udane
		$query_password = $result[0]['HASLO'][0];
		if ($input_password == sha1($query_password)) {
			$_SESSION['auth'] = '';
			$_SESSION['login_exists'] = '';
			setcookie('active_username', $input_username);
			header('Location:index.php');
		}
		//złe hasło
		else {
			$_SESSION['auth'] = 'false';
			header('Location:login_page.php');
		}
	}
}
oci_close($conn);
