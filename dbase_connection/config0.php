<?php
	require "class_db_mysql.php";
	$dbhost = "dd30306.kasserver.com";
	$dbuser = "d01756fe";
	$dbpassword = "z2MvLTSETR4dCBh6";
	$dbbase = "d01756fe";
/*
		$dbhost = "localhost";
		$dbuser = "root";
		$dbpassword = "";
		$dbbase = "KerbanFlags";
*/

$db = new db($dbhost, $dbuser, $dbpassword, $dbbase);
														// Safety Reasons
														$dbhost = "DENIED";
														$dbuser = "DENIED";
														$dbpassword = "DENIED";
														$dbbase = "DENIED";



?>