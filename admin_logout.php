<?php

session_start();
$_SESSION['key_admin'] = "";
header('Location: admin_page.php');
?>