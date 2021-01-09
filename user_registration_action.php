<?php session_start();
require_once('database_info.php');

$conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS");

$username = $_POST['username'];

$result = oci_parse($conn, "SELECT * FROM gracze WHERE nick = '$username';");
oci_execute($result, OCI_NO_AUTO_COMMIT);

$count = oci_num_rows($result);

//zła nazwa użytkownika
if ($count != 0) 
{
	$_SESSION['available'] = 'false';
	header('Location:registration_page.php');
} 
else 
{
    $password = $_POST['password'];
    $password_rep = $_POST['password_repeat'];
    
	if ($password != $password_rep) {
		$_SESSION['password_match'] = 'false';
		header('Location:registration_page.php');
	}

	else {
		$password = sha1($password);

		//wstawianie nowego użytkownika do bazy
		$insert = oci_parse($conn, "INSERT INTO gracze(nick, haslo, typ_gracza) VALUES ('$username', '$password', 'użytkownik');");
        oci_execute($insert);
        oci_commit($conn);
        echo "<script type='text/javascript'>alert('Poprawnie zarejestrowano!');</script>";
        header('Location:login_page.php');
      
	}
}

oci_close($conn);
