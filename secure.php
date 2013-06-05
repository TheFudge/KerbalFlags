<?php
// Admin Control Panel
// for official filmrausch Website at filmrausch.hdm-stuttgart.de
//
// USAGE ALLOWED ONLY AT THE FOLLOWING DOMAINS:
// http://test.kasimir-blust.de
// http://filmrausch.hdm-stuttgart.de
//
// Created and Developed by (c) Kasimir Blust, 2013
//
// File: secure.php@acp
// Usage: ACP Loader Script

require_once "debug.php";

$debug = false;
$sqldebug = false;
// Loading basic classes, functions and variables
	$errorlink = "modules/error/error.php"; // Loading error link
	require "dbase_connection/config0.php"; // MySQL Connection $db->query, $db->fetch_array, $db->num_rows

	//REQUIRE "modules/globals/fetch_them.php"; // Load global variables
	
	REQUIRE "modules/safelog/kb_lychee.php"; // Loading IPC - Password Engine
	$lychee = new IntelligentPasswordGenerator_lychee();
	
	/*REQUIRE "modules/globals/class_date.php"; // Loading DATE FORMATTING CLASS
	$date = new datum();*/

	// INITIALISING LOG METHOD
	/*function logfile($logtext, $inhalt)
 	{
 			global $user, $db;
		$db->query("INSERT INTO  `kerbanlogfiles` (`logID` ,`author` ,`time` ,`info`)
				    VALUES (NULL ,  '".$user['userID']."',  '".time()."', '{$logtext}|".mysql_real_escape_string($inhalt)."|{$user['name']}');");
 	}*/
	
	if(strlen($_GET['page']) > 2) $page = $_GET['page'];
	else $page = "start";

	if(isset($_GET['option'])) if(strlen($_GET['option']) > 2) $option = $_GET['option'];
	else $option = "start"; else $option = "start";

	if(isset($_GET['step'])) if(strlen($_GET['step']) > 0) $step = $_GET['step'];
		else $step = "start"; else $step = "start";

// Checking if user is (still) online
if(isset($_COOKIE['filmrausch_acp_lychee']))
{
	$sql = $db->query("SELECT * FROM kerbanuser WHERE lychee_log='".mysql_real_escape_string($_COOKIE['filmrausch_acp_lychee'])."'");
	if($db->num_rows($sql) == 1)
	{
		$user = $db->fetch_array($sql);
		if($_SERVER['HTTP_USER_AGENT'] == $user['HTTP_USER_AGENT'])
		{
			setcookie("filmrausch_acp_lychee", $lychee->cid, time()+3600);
			$db->query("UPDATE kerbanuser SET lychee_log = '".$lychee->cid."' WHERE userID='$user[userID]'");
			$lychee->destroy();
		}
		else
		{
			header("LOCATION: index.php?error=agent");
			exit;
		}
	}
	else
	{
		header("LOCATION: index.php?error=manipulation");
		exit;
	}
}
else
{
	header("LOCATION: index.php?error=timeout");
	exit;
}

// LOAD PAGE MODULES
if(is_FILE("modules/$page/$page.php"))
{
	// Page exists, load the stuff
	?>
	<!DOCTYPE html>
	<?php
	require "modules/$page/$page.php";
}
else
{
	// Page doesn't exist, load error module
	?>
	<!DOCTYPE html>
	<?php
	$error = "Module does not exist!";
	require "$errorlink";
}
?>