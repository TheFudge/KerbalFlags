<?php
// Admin Control Panel
// for official filmrausch Website at filmrausch.hdm-stuttgart.de
//
// Created and Developed by (c) Kasimir Blust, 2013
//
// File: header.php@templates@modules
// Usage: Header file

function active_header($linkpagename, $href, $title, $external=0)
{
	global $page;
	if($external == 0)
	{
		if($page == $linkpagename)
		{
			echo "<li class=\"active\"><a href=\"$href\">$title</a></li>";
		}
		else
		{
			echo "<li><a href=\"$href\">$title</a></li>";
		}
	}
	elseif($external == 1)
	{
		echo  $title;
	}
}

?>
<html>
	<head>
		<title>KerbalFlags - Overview</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel="shortcut icon" type="image/x-icon" href="img/icon.png">
		<link type="text/css" rel="Stylesheet" href="css/bootstrap.css" />
		<link type="text/css" rel="Stylesheet" href="css/bootstrap-responsive.css" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<style type="text/css">
			body
			{
				background-color: #f1f1f1;
			}
			#main
			{
				margin: 0 auto;
				margin-top: 5%;
				width: 70%;
				
				border: 1px solid grey;
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
			<div class="navbar">
			  <div class="navbar-inner">
				<a class="brand" title="Global Flag Exchange Market" href="secure.php?page=myflags">KerbalFlags</a>
				<ul class="nav">
				  <?php
					active_header("start", "secure.php?page=start", "Overview");
					?>
					<li class="divider-vertical"></li>
					<?php
					
						active_header("myflags", "secure.php?page=myflags", "My Flags");
						active_header("myfriends", "secure.php?page=myfriends", "My Friends");
						active_header("settings", "secure.php?page=settings", "Settings");
						active_header("help", "secure.php?page=help", "Help");
					?>
					</ul>
				<ul class="nav pull-right">
				<li class="divider-vertical"></li>
					<?php
					active_header("downloads", "secure.php?page=downloads", "Downloads");
					active_header("logout", "secure.php?page=logout", "Logout");
				?>
				</ul>
			  </div>
			</div>
