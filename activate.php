<?php
// Admin Control Panel
// for official filmrausch Website at filmrausch.hdm-stuttgart.de
//
// Created and Developed by (c) Kasimir Blust, 2013
//
// File: activate.php@acp
// Usage: Activation process: User Creation, Admin Vertification, User Vertification, Lost Passwort Management
require "debug.php";

$debug = false;

$output = "";

require "dbase_connection/config0.php";


if(isset($_GET['error']))
{
if(strlen($_GET['error']) >= 1)
{
	$err['timeout'] = "Your Session has expired. Please log in again";
	if(isset($err[$_GET['error']]))
		$output .= "<div class=\"alert alert-error\">".$err[$_GET['error']]."</div>";
	else
		$output .= "<div class=\"alert alert-error\">Unknown error. Please try again</div>";
}}
if(strlen($_GET['page']) > 2) $page = $_GET['page'];
	else $page = "forgot";

	
if($page == "forgot-check")
{
	$sql = $db->query("SELECT * FROM kerbanuser WHERE user='".mysql_real_escape_string($_POST['kzl_acp_filmrausch'])."'");
	if($db->num_rows($sql) == 1)
	{
		$user=$db->fetch_array($sql);
		$rand = rand(111111, 999999);
		$db->query("UPDATE kerbanuser SET password='new', lychee_log='$rand' WHERE userID = '$user[userID]'");
		$link = $db->host()."/activate.php?page=forgot-new&secureid=$rand&kzl=$user[user]";
		$empfaenger = $user['mail'];
		$betreff = "Forgot your password - KerbanFlags";
		$nachricht = "Hello $user[user], 
a few minutes ago, you told us that you forgot your password.
Because of this iincident, we now send the reactivation link to set up a new password.



Under the following link you can reset your password and enter a new one.

$link

If you have to try it manually, that is the SecurityCode: $rand



If you should not receive this mail, please contact us.


This Mail is automatically generated.
Only answer if you have a problem.";
		$header = 'From: kerbanFlag@noreply.de' . "\r\n" .
			'Reply-To: support@kasimir-blust.de' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		mail($empfaenger, $betreff, $nachricht, $header);
		mail("support@kasimir-blust.de", "KerbanFlag - Passwort vergessen von $user[user]", "Zur Notifikation gesendet.", $header);
		$output .= "<div class=\"alert alert-success\">Now check your mails. It could take up to 10 minutes to receive a mail. If you have a problem, contact support@kasimir-blust.de.</div>";
	}
	else
	{
		$page = "forgot";
		$output .= "<div class=\"alert alert-error\">The mail address is not in our database!</div>";
	}
}
if($page == "forgot")
{
	$output .= "
	<form action=\"activate.php?page=forgot-check\" method=\"post\" class=\"form-horizontal\">
			<legend>Forgot Your Password</legend>
			  <div class=\"control-group\">
				<label class=\"control-label\" for=\"kzl_login\">Enter Username:</label>
				<div class=\"controls\">
				  <input type=\"text\" name=\"kzl_acp_filmrausch\" id=\"kzl_login\" placeholder=\"Username\" required />
				</div>
			  </div>
			  <div class=\"control-group\">
				<div class=\"controls\">
				<span class=\"help-block\">If your username is in our database, you will receive a notification to the mail adress you've set.</span><br>

				  <button type=\"submit\" class=\"btn\">Check it</button>
				</div>
			  </div>
			</form>";
}
if($page == "forgot-new")
{
	if(isset($_GET['try']))
		{if($_GET['try'] == "again")
		$sql = $db->query("SELECT * FROM kerbanuser WHERE user='".mysql_real_escape_string($_POST['kzl'])."' AND lychee_log='".mysql_real_escape_string($_POST['secureid'])."'");
		else
		$sql = $db->query("SELECT * FROM kerbanuser WHERE user='".mysql_real_escape_string($_GET['kzl'])."' AND lychee_log='".mysql_real_escape_string($_GET['secureid'])."'");
	}
	else
		$sql = $db->query("SELECT * FROM kerbanuser WHERE user='".mysql_real_escape_string($_GET['kzl'])."' AND lychee_log='".mysql_real_escape_string($_GET['secureid'])."'");
	if($db->num_rows($sql) == 1)
	{
			
		$user=$db->fetch_array($sql);
		$output .= "<form action=\"activate.php?page=forgot-new-check&id=$user[userID]&secureid=$user[lychee_log]\" method=\"post\" class=\"form-horizontal\">
			<legend>Enter your Password</legend>
			  <div class=\"control-group\">
				<label class=\"control-label\" for=\"pw_new\">New Password:</label>
				<div class=\"controls\">
				  <input type=\"password\" name=\"pw1_acp_filmrausch\" id=\"pw_new\" placeholder=\"Password\" required>
				</div>
			  </div>
			  <div class=\"control-group\">
				<label class=\"control-label\" for=\"pw2_new\">Retype Password:</label>
				<div class=\"controls\">
				  <input type=\"password\" name=\"pw2_acp_filmrausch\" id=\"pw2_new\" placeholder=\"Password\" required>
				</div>
			  </div>
			  <div class=\"control-group\">
				<div class=\"controls\">
				<span class=\"help-block\">After this step, you can log in again imediately.</span><br>
				  <button type=\"submit\" class=\"btn\">Next</button>
				</div>
			  </div>
			</form>";
	}
	else
	{
		$output .= "
		<div class=\"alert alert-error\">Your data is wrong. Try again</div>
		<form action=\"activate.php?page=forgot-new&try=again\" method=\"post\" class=\"form-horizontal\">
			<legend>Enter your Password</legend>
			  <div class=\"control-group\">
				<label class=\"control-label\" for=\"id\">Username</label>
				<div class=\"controls\">
				  <input type=\"text\" name=\"id\" id=\"id\" placeholder=\"Username\" required>
				</div>
			  </div>
			  <div class=\"control-group\">
			  
				<label class=\"control-label\" for=\"secureid\">Enter the Security Key:</label>
				<div class=\"controls\">
				<span class=\"help-block\">That Key was sent to you in the mail. It is a number with 6 digits.</span>
				  <input type=\"text\" name=\"secureid\" id=\"secureid\" placeholder=\"123456\" required>
				</div>
			  </div>
			  <div class=\"control-group\">
				<div class=\"controls\">
				  <button type=\"submit\" class=\"btn\">Check Again</button>
				</div>
			  </div>
			</form>
		";
	}
}
if($page == "forgot-new-check")
{
	REQUIRE_ONCE "modules/safelog/kb_lychee.php"; // Loading IPC - Password Engine
	$lychee = new IntelligentPasswordGenerator_lychee();
	
	$_POST['pw1_acp_filmrausch'] = md5($_POST['pw1_acp_filmrausch']);
	$_POST['pw2_acp_filmrausch'] = md5($_POST['pw2_acp_filmrausch']);
	if($_POST['pw1_acp_filmrausch'] == $_POST['pw1_acp_filmrausch'])
	{
		$db->query("UPDATE kerbanuser SET password = '".$lychee->generate($_POST['pw1_acp_filmrausch'], 0)."' WHERE lychee_log='".mysql_real_escape_string($_GET['secureid'])."' and userID='".mysql_real_escape_string($_GET['id'])."'") or die("Error. Please give us the right data!");
		header("LOCATION: index.php");
		exit;
	}
	else
	{
		$output .= "<form action=\"activate.php?page=forgot-new-check&id=".$_GET['id']."&secureid=".$_GET['secureid']."\" method=\"post\" class=\"form-horizontal\">
			<legend>Enter Password</legend>
			  <div class=\"control-group\">
				<label class=\"control-label\" for=\"pw_new\">New Password:</label>
				<div class=\"controls\">
				  <input type=\"text\" name=\"pw1_acp_filmrausch\" id=\"pw_new\" placeholder=\"Password\" required>
				</div>
			  </div>
			  <div class=\"control-group\">
				<label class=\"control-label\" for=\"pw2_new\">Retype Password:</label>
				<div class=\"controls\">
				  <input type=\"text\" name=\"pw2_acp_filmrausch\" id=\"pw2_new\" placeholder=\"Password\" required>
				</div>
			  </div>
			  <div class=\"control-group\">
				<div class=\"controls\">
				<span class=\"help-block\">After this step, you can log in again imediately.</span><br>
				  <button type=\"submit\" class=\"btn\">Next</button>
				</div>
			  </div>
			</form>";
	}
}
if($page == "new")
{
	if(strlen($_POST['pw_acp_filmrausch']) < 5 || $_POST['pw_acp_filmrausch'] != $_POST['wdh_acp_filmrausch'] || strlen($_POST['nm_acp_filmrausch']) < 5)
	{
		header("LOCATION: index.php?error=activate");
		exit;
	}
	$sql = $db->query("SELECT user FROM kerbanuser WHERE user='".mysql_real_escape_string($_POST['kzl_acp_filmrausch'])."' ");
	if($db->num_rows($sql) == 1)
	{
		$user = $db->fetch_array($sql);
		if($user['lychee_log'] == "activation_pending")
			header("LOCATION: index.php?error=activation_pending");
		else header("LOCATION: index.php?error=activate_existence");
		exit;
	}
	else
	{
		$sql = $db->query("SELECT user FROM kerbanuser WHERE mail='".mysql_real_escape_string($_POST['mail_acp_filmrausch'])."' ");
		if($db->num_rows($sql) == 1)
		{
			header("LOCATION: index.php?error=activate_existence_m");
			exit;
		}
		$sql = $db->query("SELECT user FROM kerbanuser WHERE company='".mysql_real_escape_string($_POST['nm_acp_filmrausch'])."' ");
		if($db->num_rows($sql) == 1)
		{
			header("LOCATION: index.php?error=activate_existence_com");
			exit;
		}
		$sql = $db->query("SELECT user FROM kerbanuser WHERE short='".mysql_real_escape_string($_POST['nm2_acp_filmrausch'])."' ");
		if($db->num_rows($sql) == 1)
		{
			header("LOCATION: index.php?error=activate_existence_s");
			exit;
		}
		require "modules/safelog/kb_lychee.php";
		$rand = rand(100000,999999);
		$lychee = new IntelligentPasswordGenerator_lychee();
		$db->query("INSERT INTO  `kerbanuser` (
			`userID` ,
			`user` ,
			`password` ,
			`lychee_log` ,
			`mail`,
			`company`,
			`short`,
			`HTTP_USER_AGENT`
			)
			VALUES (
			NULL ,  '".mysql_real_escape_string($_POST['kzl_acp_filmrausch'])."',  '".$lychee->generate(md5($_POST['pw_acp_filmrausch']), 0)."',  '".$rand."',  '".mysql_real_escape_string($_POST['mail_acp_filmrausch'])."',  '".mysql_real_escape_string($_POST['nm_acp_filmrausch'])."',  '".mysql_real_escape_string($_POST['nm2_acp_filmrausch'])."'
			,  'activation_pending');");
			
			$_POST['pw_acp_filmrausch'] = "DENIED";
			$_POST['wdh_acp_filmrausch'] = "DENIED";
			$lychee->destroy();
			$empfaenger = $_POST['mail_acp_filmrausch'];
			$betreff = "KerbanFlags - Activate";
			$nachricht = "Hello {$_POST['kzl_acp_filmrausch']}.
Thank you for using KerbanFlags.

To validate your E-Mail address click on the link and type in your password.
$httpAddress/activate.php?page=activate_mail&user=" . $_POST['kzl_acp_filmrausch']."&secureid=$rand


This is a automatically generated mail.
";			$header = 'From: kerbanFlags@noreply.de' . "\r\n" .
				'Reply-To: support@kasimir-blust.de' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

			mail($empfaenger, $betreff, $nachricht, $header);
			mail("support@kasimir-blust.de", "KerbanFlag - User Registration {$_POST['kzl_acp_filmrausch']}", "Notifikation vom User Register Script.
Infos:
VALUES:
'".mysql_real_escape_string($_POST['kzl_acp_filmrausch'])."',
'".$lychee->generate(md5($_POST['pw_acp_filmrausch']), 0)."', (not the real one)
'activation_pending',
'".mysql_real_escape_string($_POST['mail_acp_filmrausch'])."',
'".mysql_real_escape_string($_POST['nm_acp_filmrausch'])."',
'".mysql_real_escape_string($_POST['nm2_acp_filmrausch'])."'

#EOF#", $header);
		
		$output .= "<form action=\"#\" class=\"form-horizontal\">
			<legend>User registration almost complete</legend>
			  </form>
				<div class=\"alert alert-success\"><strong><big>You've succesfully registeres</big></strong><br/>We just sent you an activation notice to ".$_POST['mail_acp_filmrausch'].".
				  <a href=\"index.php\" class=\"btn btn-success\">Go to main page</a>
				</div>";
	}
}
if($page == "activate_mail")
{	
	if(!isset($_GET['check']))
	{
		if(isset($_GET['user'])) $user = $_GET['user'];
		else $user = $_POST['u'];
		$output .= "<form action=\"activate.php?page=activate_mail&check=pw\" method=\"post\" class=\"form-horizontal\">
			  <div class=\"control-group\">
				<label class=\"control-label\" for=\"u\">Your Username</label>
				<div class=\"controls\">
				  <input type=\"text\" name=\"u\" id=\"u\" value=\"$user\" required>
				</div>
			  </div>
			  <div class=\"control-group\">
				<label class=\"control-label\" for=\"s\">The Security Code</label>
				<div class=\"controls\">
				  <input type=\"text\" name=\"s\" id=\"s\" value=\"{$_GET['secureid']}\" required>
				</div>
			  </div>
			  <div class=\"control-group\">
				<div class=\"controls\">
				  <button type=\"submit\" class=\"btn\">Activate</button>
				</div>
			  </div>
			</form>";
	}
	elseif(isset($_GET['check']) && $_GET['check'] == "pw")
	{
		require "modules/safelog/kb_lychee.php";
		$lychee = new IntelligentPasswordGenerator_lychee();

		$sql = $db->query("SELECT userID, user, password, lychee_log FROM kerbanuser WHERE user='".mysql_real_escape_string($_POST['u'])."' AND lychee_log='".mysql_real_escape_string($_POST['s'])."'");
		if($db->num_rows($sql) == 1)
		{
			$db->query("UPDATE kerbanuser SET lychee_log='awaiting_first_login' WHERE user='".mysql_real_escape_string($_POST['u'])."'");
			$handle = "DENIED!";
			$_POST['p'] = "DENIED";
			$_POST['s'] = "DENIED";
			
			
			//logfile("user_new", $user['name']);
			$output .= "<form action=\"#\" class=\"form-horizontal\">
			<legend>User activated</legend>
			  </form>
				<div class=\"alert alert-success\"><strong><big><big>You've been activated!</big></big></strong><br/>You can log in now.</span><br>
				  <a href=\"index.php\" class=\"btn btn-success\">Go to the main page.</a>
				</div>";
		}
		else
		{
			echo "<br>ERROR!<br>";
			debug("var", $_POST);
			exit;
			header("LOCATION: $httpAddress/activate.php?page=activate_mail&user=" . $_POST['u']."&secureid=".$_POST['s']);
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>KerbanFlags - Activation Center</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png">
		<link type="text/css" rel="Stylesheet" href="css/bootstrap.css" />
		<style type="text/css">
			body
			{
				background-color: #f1f1f1;
			}
			#main
			{
				margin: 0 auto;
				margin-top: 5%;
				width: 60%;
				
				border: 1px solid black;
				border-radius: 5px;
				box-shadow: 2px 2px 10px grey;
				background-color: #fff;
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
			<?php echo $output; ?>
		</div>
	</body>
</html>