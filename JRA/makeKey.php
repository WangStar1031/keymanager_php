<?php

function makeEncryptKey($_keyword){
	if( $_keyword == "")return "";
	$_key1 = crypt(time(), "");
	$_key2 = crypt($_keyword, "");
	$_key3 = crypt(date("Ymd"), "");
	$key =  $_key1 . $_key2 . $_key3;
	$key = str_replace("$", "", $key);
	$key = str_replace(".", "", $key);
	$key = str_replace("/", "", $key);
	// echo $key;
	return $key;
}

?>