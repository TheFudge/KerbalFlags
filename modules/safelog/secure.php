<?php
require "kb_loader.php";
require "safelog/generator.php";

if($action == "start")
{
	eval("\$tpl->output(\"".$tpl->get("start")."\");");
	exit;

}
if($action == "contact")
{
	eval("\$tpl->output(\"".$tpl->get("kontakt")."\");");
	exit;
}
if($action == "login")
{
	eval("\$tpl->output(\"".$tpl->get("login")."\");");
	exit;
}
if($action == "activate")
{
	if(isset($_GET['n']))$name = $_GET['n']; else $name="";
	if(isset($_GET['m']))$mail = $_GET['m']; else $mail="";
	if(isset($_GET['u']))$num = $_GET['u']; else $num="";
	$log = '';
	eval("\$tpl->output(\"".$tpl->get("activate")."\");");
	exit;
}
if($action == "activation")
{
	$name = $_POST['n'];
	$mail = $_POST['m'];
	$num = $_POST['u'];
	$log = $_POST['l'];
	$pw = md5($_POST['password']);
	
	$sql = $db->query("SELECT * FROM user_data WHERE name='".mysql_real_escape_string($name)."' AND userID='".mysql_real_escape_string($num)."' AND mail='".mysql_real_escape_string($mail)."' AND log='".mysql_real_escape_string($log)."'");
	if($db->num_rows($sql) == 0)
	{
		// WRONG INPUT
		problem("Die angegebenen Daten sind inkorrekt.<br>Überprüfen Sie Ihre Eingabe!");
		eval("\$tpl->output(\"".$tpl->get("activate")."\");"); // OUTPUT
		exit;
	}
	
	$secure['sid'] = lynchee_session_create(3, "", lynchee_seed_create(), lynchee_seed_create()); // Creating GET session_info from a normal seed like 123.
	$secure['cid'] = lynchee_session_create(2, "kb_cherry_lychee", (time()%lynchee_seed_create())*lynchee_seed_create(), lynchee_seed_create()); // Creating very long COOKIE session_info from the time time and random seeds.
	$db->query("UPDATE user_data SET log='{$secure['sid']}{$secure['cid']}', pw = '".IPC_generator_generate($pw, 0)."' WHERE userID = '".mysql_real_escape_string($num)."'");
	
	$secure['sid'] = "?kb_cherry_secure=".$secure['sid'];
	setcookie("kb_cherry_secure", $secure['cid'], time()+1800);
	
	// AUTOMATED REDIRECT PER META TAG
	$link = "user.php$secure[sid]&action=view";
	eval ("\$head = \"".$tpl->get("head_refresh")."\";"); // INDIVIDUAL HEADER FOR META REFRESH
	eval("\$tpl->output(\"".$tpl->get("refresh")."\");"); // OUTPUT
	exit;
}
if($action == "logger")
{
	//Login Script
	
	$pw_eingabe = md5($_POST['password']);
	$username = $_POST['name'];
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";
	
	// Checking if it is the name or the email or the user_number and if he is in database
	$sql = $db->query("SELECT * FROM user_data WHERE name='".mysql_real_escape_string($username)."'");
	if($db->num_rows($sql) == 0)
	{
		$sql = $db->query("SELECT * FROM user_data WHERE userID = '".mysql_real_escape_string($username)."'");
		if($db->num_rows($sql) == 0)
		{
			$sql = $db->query("SELECT * FROM user_data WHERE mail = '".mysql_real_escape_string($username)."'");
			if($db->num_rows($sql) == 0)
			{
				// WRONG INPUT
				problem("Zu diesen Logindaten existiert kein Benutzer.");
				eval("\$tpl->output(\"".$tpl->get("login")."\");"); // OUTPUT
				exit;
			}
		}
		
	}
	$user = $db->fetch_array($sql);
	$pw = explode("#", $user['pw']); // GET PW AND SEED
	$pw_eingabe = IPC_generator_generate($pw_eingabe, $pw[1]);

	if($pw[0] != $pw_eingabe)
	{
		problem("Zu diesen Logindaten existiert kein Benutzer.<br>(b)");
		// PW IS NOT CORRECT
		eval("\$tpl->output(\"".$tpl->get("login")."\");"); // OUTPUT
		exit;
	}
	
	// SETTING SAFELOG DATA
	$secure['sid'] = lynchee_session_create(3, "", lynchee_seed_create()*2, lynchee_seed_create()); // Creating GET session_info from a normal seed like 123.
	$secure['cid'] = lynchee_session_create(2, "kb_cherry_lychee", (time()%lynchee_seed_create())*lynchee_seed_create(), lynchee_seed_create()); // Creating very long COOKIE session_info from the time time and random seeds.
	$db->query("UPDATE user_data SET log='{$secure['sid']}{$secure['cid']}' WHERE userID='$user[userID]'");
	
	$secure['sid'] = "?kb_cherry_secure=".$secure['sid'];
	setcookie("kb_cherry_secure", $secure['cid'], time()+1800);
	
	
	// AUTOMATED REDIRECT PER META TAG
	$link = "user.php$secure[sid]&action=overview";
	
	eval ("\$head = \"".$tpl->get("head_refresh")."\";"); // INDIVIDUAL HEADER FOR META REFRESH
	
	eval("\$tpl->output(\"".$tpl->get("refresh")."\");"); // OUTPUT
	exit;
}
if($action == "adminmail")
{
	if(!isset($_GET['step']))
	{
		$name = $_POST['name'];
		$mail = $_POST['mail'];
		$reason = $_POST['reason'];
		problem("Die Kontaktart: ".getlng("reason:$reason")." ist zurzeit nicht verfügbar.<br>Bitte benutzen Sie unten stehendes Alternativ Forumlar.");
		
		$handle = explode("#", lynchee_generate(lynchee_seed_create(), lynchee_seed_create()));

		$secureID = $handle[0];
		$handle[0] = ""; $handle[1] = "";
		$db->query("INSERT INTO  `contact_data` (
	`contactID` ,`status` ,`secureID` ,`reason` ,`name` ,`email` ,`betreff` ,`text`)
	VALUES (NULL ,  '-5',  '$secureID', '".mysql_real_escape_string($reason)."',  '.".mysql_real_escape_string($name)."',  '.".mysql_real_escape_string($mail)."',  'notset',  'notset');");
		$reason_speech = getlng("reason:$reason");
		
		setcookie("cherry_secure_contact", $secureID, time+3600);
		
		eval("\$tpl->output(\"".$tpl->get("kontakt_error")."\");");
		exit;
	}
	$s = $_GET['step'];
	if($s == 2) // Kontaktart nicht verfügbar, Standard Nachricht
	{
		if(!isset($_COOKIE['cherry_secure_contact']))
			error("Session expired", "Die Zeit Ihres Aufenthalts ist abgelaufen. Bitte klicken Sie erneut auf 'Kontakt' und führen Sie alle Schritte erneut aus.");
		$secID = $_COOKIE['cherry_secure_contact'];
		$sql = $db->query("SELECT * FROM contact_data WHERE secureID='$secID'");
		if($db->num_rows($sql) == 0)
			error("Fehlerhafte Eingabe", "Da ist wohl etwas schiefgelaufen. Bitte vergewissern Sie sich, dass Ihre Cookies eingeschaltet sind.");
	}
}
if($action == "impressum")
{
	eval("\$tpl->output(\"".$tpl->get("impressum")."\");");
	echo $time->end();
	exit;
}
eof();
?>