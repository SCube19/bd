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
	$input_username = $_POST['username'];
	$input_password = $_POST['password'];
	$input_password_repeat = $_POST['password_repeat'];

	$result = oci_parse($conn, "SELECT * FROM gracze WHERE nick='" . $input_username . "'");
	oci_execute($result);

	$numrows = oci_fetch_all($result, $res);

	if ($numrows != 0) {
		$_SESSION['available'] = 'false';
		header('Location:klient_rejestracja.php');
	} else if ($input_password != $input_password_repeat) {
		$_SESSION['password_match'] = 'false';
		header('Location:registration_page.php');
	} else {
		//wstawianie nowego użytkownika do bazy
		query($conn, "INSERT INTO gracze(nick, haslo, typ_gracza) VALUES ('".$input_username."','".$input_password."', 'uzytkownik')");
		$_SESSION['alert'] = 'true';
		header('Location:login_page.php');
	}
}
