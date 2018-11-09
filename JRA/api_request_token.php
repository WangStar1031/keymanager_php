<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once __DIR__ . "/makeKey.php";

$keyword = "";
if( isset($_GET['keyword'])) $keyword = $_GET['keyword'];
if( isset($_POST['keyword'])) $keyword = $_POST['keyword'];

if( $keyword != ""){
	echo makeEncryptKey($keyword);
}

?>