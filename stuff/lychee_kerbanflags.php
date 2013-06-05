<?php
// Admin Control Panel
// for official filmrausch Website at filmrausch.hdm-stuttgart.de
//
// Created and Developed by (c) Kasimir Blust, 2013
//
// File: check.php@acp
// Usage: Check the Password and redirecting
require "../debug.php";
$debug = false;

if(strlen($_GET['pw']) <= 3)
{
	echo -1;
	exit;
}
require "../dbase_connection/config0.php";

REQUIRE "../modules/safelog/kb_lychee.php"; // Loading IPC - Password Engine
$lychee = new IntelligentPasswordGenerator_lychee();


$pw = md5($_GET['pw']);
$_GET['pw'] = "denied";
$user = $_GET['user'];
$_GET['user'] = "denied";

$sql = $db->query("SELECT userID, user, password, lychee_log, HTTP_USER_AGENT FROM kerbanuser WHERE user='".mysql_real_escape_string($user)."'");
if($db->num_rows($sql) != 1)
{
	echo -2;
	exit;
}
$user = $db->fetch_array($sql);
// echo "<pre>";
// print_r($user);
// echo "</pre>";

if($user['HTTP_USER_AGENT'] == "activation_pending")
{
	echo -3;
	exit;
}
$seed = explode("#", $user['password']);

if($lychee->generate($pw, $seed[1])."#".$seed[1] != $user['password'])
{
	$user['password'] = "DENIED";
	$user['kuerzel'] = "DENIED";
	$user['userID'] = "DENIED";
	$seed[0] = "DENIED"; $seed[1] = "DENIED";
	echo -4;
	exit;
}

$lychee->destroy();

echo $user['userID'];
?>