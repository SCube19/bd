<?php
session_start();
require_once('database_info.php');
require_once('query.php');

if ($_COOKIE['last_page'] == '')
	setcookie('last_page', 'index.php');

if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS"))) {
	header('Location:error_page.php');
	exit;
}

//dane z formularza do zmiennych
$input_username = $_POST['username'];
$input_password = sha1($_POST['password']);


// //hasowanie hasla

//zapytanie
$result = query($conn, "SELECT * FROM gracze WHERE nick='" . $input_username . "'");
oci_close($conn);

//zła nazwa użytkownika
if ($result[1] == 0) {
	$_SESSION['login_exists'] = 'false';
	header('Location:login_page.php');
	exit;
} else {
	//logowanie udane
	$query_password = $result[0]['HASLO'][0];
	if ($input_password == $query_password) {
		$_SESSION['auth'] = '';
		$_SESSION['login_exists'] = '';
		setcookie('active_username', $input_username);
		header('Location:' . $_COOKIE['last_page']);
		exit;
	}
	//złe hasło
	else {
		$_SESSION['auth'] = 'false';
		header('Location:login_page.php');
		exit;
	}
}
