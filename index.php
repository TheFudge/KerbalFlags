<?php
require_once("debug.php");
// Admin Control Panel
// for official filmrausch Website at filmrausch.hdm-stuttgart.de
//
// Created and Developed by (c) Kasimir Blust, 2013
//
// File: index.php@acp
// Usage: Login form

$debug = false;
$httpAddress = "http://localhost/KerbanFlags";
$output = "";
if(isset($_GET['error']))
{
if(strlen($_GET['error']) >= 1)
{
	$err['existence'] = "Either Password or Username is wrong.<br><a href=\"activate.php?page=forgot\">Forgot your password?</a>";
	$err['manipulation'] = "Some data was lost in the web.";
	$err['timeout'] = "Your session has expired. Please log in again.";
	$err['not_activated'] = "Your account has not been activated yet. Please check your mail.";
	$err['activate'] = "Bitte gib ein korrektes Kürzel ein.<br>Bitte gib bei Passwort und Name mehr als 6 Zeichen ein.<br>Passwort und wiederholtes Passwort müssen übereinstimmen!";
	$err['activate_existence'] = "That username already exists! <br><a href=\"activate.php?page=forgot\">Forgot your password?</a>";
	$err['no_pw'] = "We can't guarantee for your safety with that password. It is too short";
	$err['agent'] = "Copying Cookies to another computer will not be tolerated!
	You have to login again.";
	$err['activation_pending'] = "You've been blocked from that site. I don't like annoying people.";
	if(isset($err[$_GET['error']]))
		$output = "<div class=\"alert alert-error\">".$err[$_GET['error']]."</div>";
	else
		$output = "<div class=\"alert alert-error\">Unknown error. Please try again.</div>";
}}

// echo "<pre>";
// print_R($_SERVER);
// echo "</pre>";

?>
<!DOCTYPE html>
<html>
	<head>
		<title>KerbanFlags - Login</title>
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
			<h2>KerbanFlags Flag Exchange</h2>
			<div class="alert alert-info"><strong>Beta 0.9.00!</strong><br/>KerbanFlags is under developement. Always make a Backup of your savegames on Kerbal Space Program.<br>
				<strong>Notice: </strong>KerbanFlags has been designed for HTML 5 based programming. That is a very new Web standard. If you encounter layout or design errors, please make sure that your browser is at the latest (stable) build, befor you report a problem.
				<br/>You have to accept JavaScript and Cookies for this website, too.<br/>
			<a href="http://support.kasimir-blust.de/">Found an Error? Click here.</a></div>
			<div class="alert alert-warning">Please Log in.</div>
			<?php echo $output; ?>
			<form action="check.php" method="post" class="form-horizontal">
			<legend>Login</legend>
			  <!--<div class="control-group">
				<label class="control-label" for="inputEmail">Email</label>
				<div class="controls">
				  <input type="text" id="inputEmail" placeholder="Email">
				</div>
			  </div>-->
			  <div class="control-group">
				<label class="control-label" for="kzl_login">Username:</label>
				<div class="controls">
				  <input type="text" name="kzl_acp_filmrausch" id="kzl_login" placeholder="TheUltimateKerbal" required>
				</div>
			  </div>
			   <div class="control-group">
				<label class="control-label" for="pw_login">Password:</label>
				<div class="controls">
				  <input type="password" name="pw_acp_filmrausch" id="pw_login" placeholder="Passwort" required>
				</div>
			  </div>
			  <div class="control-group">
				<div class="controls">
				  <!--<label class="checkbox">
					<input type="checkbox"> Remember me
				  </label>-->
				  <button type="submit" class="btn">Log In</button>
				</div>
			  </div>
			</form>
			<form action="activate.php?page=new" method="post" class="form-horizontal">
			<legend>Register for the Flag Exchange</legend>
			  <!--<div class="control-group">
				<label class="control-label" for="inputEmail">Email</label>
				<div class="controls">
				  <input type="text" id="inputEmail" placeholder="Email">
				</div>
			  </div>-->
			  <div class="control-group">
				<label class="control-label" for="kzl_activate">Choose a username:</label>
				<div class="controls">
				  <input type="text" name="kzl_acp_filmrausch" id="kzl_activate" placeholder="TheUltimateKerbal" required>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="mail_activate">Enter your e-mail:</label>
				<div class="controls">
				  <input type="text" name="mail_acp_filmrausch" id="mail_activate" placeholder="test@example.com" required>
				</div>
			  </div>
			   <div class="control-group">
				<label class="control-label" for="pw_active">Set up your password:</label>
				<div class="controls">
				  <input type="password" name="pw_acp_filmrausch" id="pw_active" placeholder="Passwort" required>
				</div>
			  </div>
			   <div class="control-group">
				<label class="control-label" for="wdh_active">Retype your password:</label>
				<div class="controls">
				  <input type="password" name="wdh_acp_filmrausch" id="wdh_active" placeholder="Passwort" required>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="nm_ativate">Your KSP-Company's name:</label>
				<div class="controls">
				  <input type="text" name="nm_acp_filmrausch" id="nm_ativate" placeholder="The Ultimate Space Travel" required>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="nm_ativate2">Your KSP Short name(e.G. [TUST]):<br>The shorter the better.</label>
				<div class="controls">
				  <input type="text" name="nm2_acp_filmrausch" id="nm_ativate2" placeholder="TUST" required>
				</div>
			  </div>
			  <div class="control-group">
				<div class="controls">
				  <!--<label class="checkbox">
					<input type="checkbox"> Remember me
				  </label>-->
				  <button type="submit" class="btn">Register</button>
				</div>
			  </div>
			</form>
			<a href="impressum.php" class="link">Impressum</a>
		</div>
	</body>
</html>