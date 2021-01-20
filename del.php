<?php
 session_start();

 require_once('query.php');
 require_once('database_info.php');
 if (!($conn = oci_connect($dbuser, $dbpass, "//labora.mimuw.edu.pl/LABS", 'AL32UTF8'))) {
     header("Location: error_page.php");
     exit;
 }

 $result = query($conn, "DELETE from gracze where nick='".$_COOKIE['active_username']."'");
 $result = query($conn, "DELETE from rankingBasic where nick='".$_COOKIE['active_username']."'");
 $result = query($conn, "DELETE from rankingAdvanced where nick='".$_COOKIE['active_username']."'");

 header('Location:logout.php');

?>