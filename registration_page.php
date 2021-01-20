<?php session_start();
if (isset($_COOKIE['player_username'])) {
	header('Location:index.html');
	exit;
}
?>
<!DOCTYPE html>
<html lang='pl'>

<head>
	<meta charset="utf-8">

	<title>Gry.mimuw</title>
	<meta name="description" content="gierki">
	<meta name="author" content="kk418331+kj418271">

	<link rel="stylesheet" href="styles.css">

	<link rel="shortcut icon" href="https://www.mimuw.edu.pl/sites/default/files/mim_mini.png" type="image/png">
</head>

<body>
	<div class="pagetxt">
		<div class="center">
			<div class="center2">
				<h1>Rejestracja</h1>
				<form id='user_reg' action='user_registration_action.php' method='POST'>
					Login<br>
					<input type='text' name='username' required><br><br>
					Hasło<br>
					<input type='password' name='password' required><br><br>
					Powtórz hasło<br>
					<input type='password' name='password_repeat' required><br><br>
					<input type='submit' value="ZAREJESTRUJ"><br><br>
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
				<form action="login_page.php">
					<input type="submit" value="LOGOWANIE">
				</form>
				<form action="index.php">
					<input type="submit" value="STRONA GŁÓWNA">
				</form>
			</div>
		</div>
	</div>
</body>

</html>