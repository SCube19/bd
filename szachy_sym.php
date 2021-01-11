<?php
session_start();
if (!isset($_COOKIE['active_username']))
    header('Location:login_page.php');
?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">

    <title>Gry.mimuw</title>
    <meta name="description" content="gierki">
    <meta name="author" content="SitePoint">

    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="https://www.mimuw.edu.pl/sites/default/files/mim_mini.png" type="image/png">
</head>

<body>

    <button onclick="myFunction()">SYMULUJ SZACHY</button>
    <script>
        function myFunction() {
            alert("sex");
        }
    </script>

    <div class="center">
        <a href="logout.php">WYLOGUJ</a>
        <a href='index.php'>Strona główna</a>
    </div>

</body>

</html>