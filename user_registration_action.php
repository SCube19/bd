<?php
session_start();
require_once('database_info.php');
require_once('query.php');

if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS"))) {
	header('Location:error_page.php');
	exit;
}

$input_username = $_POST['username'];
$input_password = sha1($_POST['password']);
$input_password_repeat = sha1($_POST['password_repeat']);

$result = query($conn, "SELECT * FROM gracze WHERE nick='" . $input_username . "'");

if ($result[1] != 0) {
	$_SESSION['available'] = 'false';
	header('Location:registration_page.php');
	exit;
} else if ($input_password != $input_password_repeat) {
	$_SESSION['password_match'] = 'false';
	header('Location:registration_page.php');
	exit;
} else {
	//wstawianie nowego użytkownika do bazy
	query($conn, "INSERT INTO gracze(nick, haslo, typ_gracza) VALUES ('" . $input_username . "','" . $input_password . "', 'uzytkownik')");
	$_SESSION['alert'] = 'true';
	header('Location:login_page.php');
	exit;
}
