<?php
require "./library/actions.confg";

require "library/class_time.php";
$time = new Measure_Execution_Time();
$time->init();
if($function['application'] == false)
{
	echo "Support Center currently offline... Try again later.";
	exit;
}

$time->pass("OFFLINE PASS");

if($function['secure'] == true)
	require "library/kb_lychee.php";

if($function['tpl'] == true)
{
	require "library/class_template.php";
	$tpl=new tpl(1,1);
}

if($function['tpl_extended'] == true)
	require "library/extended_tpl_class.php";

if($function['sql'] == true)
{
	require "library/class_db_mysql.php";
	require "library/config.db";
}

if($function['debug'] == true)
	require "library/kb_debugger.php";

$time->pass("LOADED");



if(isset($_GET['action'])) $action = $_GET['action'];
else $action = "start";

	$selected['start'] = "";
	$selected['impressum'] = "";
	$selected['contact'] = "";
	$selected['login'] = "";
	$selected['faq'] = "";
	$selected['overview'] = "";
	$selected['auftrag'] = "";
	if($action == "start")
		$selected['start'] = "id='selected'";
	elseif($action == "impressum")
		$selected['impressum'] = "id='selected'";
	elseif($action == "contact")
		$selected['contact'] = "id='selected'";
	elseif($action == "faq")
		$selected['faq'] = "id='selected'";
	elseif($action == "login")
		$selected['login'] = "id='selected'";
	elseif($action == "overview")
		$selected['overview'] = "id='selected'";
	elseif($action == "auftrag")
		$selected['auftrag'] = "id='selected'";
	else $selected['login'] = "id='selected'";
		

		$time->pass("SELECTED STUFF");


if(isset($_COOKIE['kb_cherry_secure']) && isset($_GET['kb_cherry_secure']))
{
	$secure['sid'] = $_GET['kb_cherry_secure'];
	$secure['cid'] = $_COOKIE['kb_cherry_secure'];
	// echo __LINE__ ." - ";
	$sql = $db->query("SELECT * FROM user_data WHERE log = '".mysql_real_escape_string($secure['sid']).mysql_real_escape_string($secure['cid'])."'");
	// echo "<br>SELECT * FROM user_data WHERE log = '".mysql_real_escape_string($secure['sid']).mysql_real_escape_string($secure['cid'])."'<br>";
	if($db->num_rows($sql) == 1)
	{
		// echo __LINE__ ." - ";
		// FETCHING USERS DATA
		$user = $db->fetch_array($sql);
		$uid = $user['userID'];
		$status = true; // USER IS ONLINE
		
		$secure['sid'] = lynchee_session_create(3, "", lynchee_seed_create()*2, lynchee_seed_create()); // Creating GET session_info from a normal seed like 123.
		$secure['cid'] = lynchee_session_create(2, "kb_cherry_lychee", (time()%lynchee_seed_create())*lynchee_seed_create(), lynchee_seed_create()); // Creating very long COOKIE session_info from the time time and random seeds.
		$db->query("UPDATE user_data SET log='{$secure['sid']}{$secure['cid']}' WHERE userID='$uid'");
		
		
		$secure['sid'] = "?kb_cherry_secure=".$secure['sid'];
		setcookie("kb_cherry_secure", $secure['cid'], time()+1800);
		// echo __LINE__ ." - ";
		
		eval ("\$head = \"".$tpl->get("head_online")."\";");
	}
	else
	{
		$status = false; // USER IS OFFLINE
		// echo __LINE__ ." - ".$_COOKIE['kb_cherry_secure']." ".$_GET['kb_cherry_secure'];
	}
}
else 
{
	$lang = "GER";
	$status = false; // USER IS OFFLINE
	// echo __LINE__ ." - ";
}

																$time->pass("ONLINE CHECK");

	if($status == true)
	{
		eval ("\$head = \"".$tpl->get("head_online")."\";");
	}
	else
	{
		eval ("\$head = \"".$tpl->get("head")."\";");
	}
	
	eval ("\$foot = \"".$tpl->get("foot")."\";");

	$noaction = true;
	
																	$time->pass("FOOT/HEAD");
	$error = false;
	function eof()
	{
		global $noaction, $status, $secure;
		if($noaction == true)
		{
			if($status == true)
			{
				header("LOCATION: ./user.php$secure[sid]&°EOF°");
				exit;
			}
			else
			{
				// header("LOCATION: ./secure.php?°EOF°");
				echo __LINE__ ." - ";
				exit;
			}
		}
		else
		{
			// dunno
		}
	}

?>