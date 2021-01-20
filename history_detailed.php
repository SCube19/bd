<?php
session_start();
$game = htmlspecialchars($_GET['game']);
$id = htmlspecialchars($_GET['id']);

$player = $_COOKIE['active_username'];
if ($player == "") {
    header("Location: " . $_COOKIE['last_page'] . ".php");
    exit;
}

setcookie('last_page', 'history_detailed.php?id=' . $id . '&game=' . $game . '');
?>

<!doctype html>

<html lang="pl">

<head>
    <meta charset="utf-8">

    <title>Gry.mimuw</title>
    <meta name="description" content="gierki">
    <meta name="author" content="kk418331+kj418271">

    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="https://www.mimuw.edu.pl/sites/default/files/mim_mini.png" type="image/png">
</head>

<body>

    
</body>

</html>