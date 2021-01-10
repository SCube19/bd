<?php 
setcookie('active_username', '', time() + (3600 * 5));
setcookie('active_password', '', time() + (3600 * 5));
header('Location:index.php');
?>