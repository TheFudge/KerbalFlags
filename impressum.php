<?php
// Admin Control Panel
// for official filmrausch Website at filmrausch.hdm-stuttgart.de
//
// Created and Developed by (c) Kasimir Blust, 2013
//
// File: impressum.php@acp
// Usage: Impressum


// echo "<pre>";
// print_R($_SERVER);
// echo "</pre>";
require "debug.php";
$debug = false;
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
			<h2>KerbanFlags</h2>
			<div class="alert alert-info"><strong>Beta 0.9.00!</strong><br/>KerbanFlags is under developement. Always make a Backup of your savegames on Kerbal Space Program.<br>
			<a href="http://support.kasimir-blust.de/">Found an Error? Click here.</a></div>
			
			<p>
			The website and the Java Application was made by:<br>
			<strong>Kasimir Blust<br>
			E-Mail: <a href="mailto:support@kasimir-blust.de">support@kasimir-blust.de</a><br></strong>
			<i>(Further data only on demand)<br><br></i>
			This Website uses <a href="http://twitter.github.io/bootstrap/">the Twitter Bootstrap Plugin</a>.<br><br>
			We will never exchange or send your private data to a third party.<br/><br/>
			<br/><br/>
			All rights reserved.
			</p>
			
			<a href="index.php">Go to the main page</a>
		</div>
	</body>
</html>