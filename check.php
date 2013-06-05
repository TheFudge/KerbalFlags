<?php
// Admin Control Panel
// for official filmrausch Website at filmrausch.hdm-stuttgart.de
//
// Created and Developed by (c) Kasimir Blust, 2013
//
// File: check.php@acp
// Usage: Check the Password and redirecting
require "debug.php";
$debug = false;

if(strlen($_POST['pw_acp_filmrausch']) <= 3)
{
	header("LOCATION: index.php?error=no_pw");
	exit;
}
require "dbase_connection/config0.php";

REQUIRE "modules/safelog/kb_lychee.php"; // Loading IPC - Password Engine
$lychee = new IntelligentPasswordGenerator_lychee();


$pw = md5($_POST['pw_acp_filmrausch']);
$_POST['pw_acp_filmrausch'] = "denied";
$user = $_POST['kzl_acp_filmrausch'];
$_POST['kzl_acp_filmrausch'] = "denied";

$sql = $db->query("SELECT userID, user, password, lychee_log, HTTP_USER_AGENT FROM kerbanuser WHERE user='".mysql_real_escape_string($user)."'");
if($db->num_rows($sql) != 1)
{
	header("LOCATION: index.php?error=existence");
	exit;
}
$user = $db->fetch_array($sql);
// echo "<pre>";
// print_r($user);
// echo "</pre>";

if($user['HTTP_USER_AGENT'] == "activation_pending")
{
	header("LOCATION: index.php?error=not_activated");
	exit;
}
$seed = explode("#", $user['password']);

if($lychee->generate($pw, $seed[1])."#".$seed[1] != $user['password'])
{
	$user['password'] = "DENIED";
	$user['kuerzel'] = "DENIED";
	$user['userID'] = "DENIED";
	$seed[0] = "DENIED"; $seed[1] = "DENIED";
	header("LOCATION: index.php?error=existence");
	exit;
}


setcookie("filmrausch_acp_lychee", $lychee->cid, time()+1800);
$db->query("UPDATE kerbanuser SET lychee_log = '".$lychee->cid."', HTTP_USER_AGENT = '".$_SERVER['HTTP_USER_AGENT']."' WHERE userID = '$user[userID]'");

$lychee->destroy();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>KerbanFlags - Login</title>
		<link type="text/css" rel="Stylesheet" href="css/bootstrap.css" />
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png">
		<meta http-equiv="refresh" content="1;URL='secure.php?page=start'">
		<style type="text/css">
			#main
			{
				margin: 0 auto;
				margin-top: 5%;
				width: 60%;
				
				border: 1px solid black;
				border-radius: 5px;
				box-shadow: 1px 1px 7px grey;
				background-color: #efefef;
				padding: 5px;
			}
			input
			{
				padding-top: +5px;
				padding-bottom: +5px;
			}
		</style>
	</head>
	
	<body>
		<div id="main">
			<h2>KerbanFlags</h2>
			<h4 class="text-success">Please whait: Redirecting...</h4>
		</div>
	</body>
</html>