<?php

session_start();
$_SESSION['key_user'] = "";
header('Location: user_page.php');
?>