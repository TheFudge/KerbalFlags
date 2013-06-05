<?php
/*
### A KASIMIR BLUST WEH/PHP APPLICATION
###
### COPYRIGHT (c) 2012, KASIMIR BLUST
###
### THIS SCRIPT IS NOT FOR OPEN SOURCE
### AND MUST NOT BE USED WITHOUT PERMISSION
###
### APP: SAFE-OWN-KB
###
### FILE: GENERATOR/PHP
### TYPE: GENRATION FUNCTIONS SCRIPT
###
THAT IS A SAFE LOG IN GENERATOR.
IT GENEREATES SAFE AND NOT EASY HACKABLE
PASSWORDS WITH A COOKIE KEY AND A HEADER
KEY FOR MATCHING THESE IN A DATABASE
USERS PASSWORD IS SAFE AND NO ONE
WILL GET HURT.
*/
$standardseed = 250;

function IPC_generator_generate($argument, $seedmode)
{
	global $standardseed;
	$argument_old =$argument;
	$argument = sha1(md5($argument));
	
	$seed = $standardseed * rand(46,89);
	
	$rand = rand(111, 999);
	$seed += $rand;
	$seed = round($seed / 100);
	
	if($seedmode != 0)
		$seed = $seedmode;
	$do= "";
	for($i = 1; $i <= $seed; $i++)
	{
		$whattodo = $seed / $i;
		$expl = explode("0", $whattodo);
		$c = count($expl);
		if($c == 0)
		{
			$argument = md5($argument);
			//$do .= "m.";
		}
		elseif($c == 1)
		{		
			$argument = md5($argument);
			//$do .= "s.";
		}
		else
		{
			$argument =sha1(md5($argument));
			//$do .= "ms.";
		}
	}
	
	$argument = sha1($argument);
	if($seedmode != 0)
		return $argument;
	else
		return $argument."#".$seed;
}
function IPC_generator_session_create($mode, $id, $username, $seed)
{
	if($mode == 1)
	{
		for($b = 1; $b <= $seed; $b++)
			$ausgang = md5($id.$username);
	}
	if($mode == 2)
	{
		for($b = 1; $b <= $seed; $b++)
			$ausgang = sha1($id.$username);
	}
	$username = 1;
	$id = 1;
	
	$c = strlen($ausgang)-1;
	$session = "0";
	for($b = 0; $b <= $c; $b++)
	{
		if(($b/2) == round($b/2))
			for($i = 0; $i <= round($seed/4+$b*2); $i++)
			{
				$crypt = sha1($ausgang[$b]);
			}
		else
		{
			for($i = 0; $i <= round($seed/3+$b); $i++)
			{
				$crypt = md5($ausgang[$b]);
			}
		}
		$session .= $crypt;
	}
	return $session;
}
?>