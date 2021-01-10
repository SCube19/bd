<?php
session_start();
require_once('database_info.php');

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
		echo "USERNAME TAKEN";
		$_SESSION['available'] = 'false';
		header('Location:klient_rejestracja.php');
	} else if ($input_password != $input_password_repeat) {
		$_SESSION['password_match'] = 'false';
		echo "PSW MATch fALSE";
		header('Location:registration_page.php');
	} else {
		echo "wszystko okej";

		//wstawianie nowego użytkownika do bazy
		$insert = oci_parse($conn, "INSERT INTO gracze(nick, haslo, typ_gracza) VALUES (:nick, :pass, 'użytkownik')");
		oci_bind_by_name($insert, ":nick", $input_username);
		oci_bind_by_name($insert, ":pass", $input_password);
		oci_execute($insert);
		oci_commit($conn);
		$_SESSION['alert'] = 'true';
		header('Location:login_page.php');
	}
}
