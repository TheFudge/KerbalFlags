<?php
/*
### A KASIMIR BLUST WEB/PHP APPLICATION
###
### COPYRIGHT (c) 2012-2013, KASIMIR BLUST
###
### THIS SCRIPT IS NOT FOR OPEN SOURCE
### AND MUST NOT BE USED WITHOUT PERMISSION
###
### APP: IPC - Intelligent Password Encryption
### ALIAS: LYCHEE
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

Notice: Everything is hackable. 
Consider first the risk of data manipulation
before implementing!
*/
class IntelligentPasswordGenerator_lychee
{
		public $sid, $cid, $standardseed;
	function destroy()
	{
		$this->sid = "DENIED";
		$this->cid = "DENIED";
		$this->standardseed = "DENIED";
	}
	function IntelligentPasswordGenerator_lychee()
	{
		$this->sid = $this->session_create(1, $this->seed_create(), $this->seed_create().$this->seed_create(), $this->seed_create());
		$this->cid = $this->session_create(1, $this->seed_create(), $this->seed_create().$this->seed_create(), $this->seed_create());
		$this->standardseed = rand(342,495);
	}
	function generate($argument, $seedmode)
	{
		$argument_old = $argument;
		$argument = sha1(md5($argument));
		
		$seed = $this->standardseed * rand(46,89);
		
		$rand = rand(111, 999);
		$seed += $rand;
		$seed = round($seed / 100);
		
		if($seedmode != 0)
			$seed = $seedmode;
		$do= "";
		//echo $seed."#".$this->standardseed;
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
	function session_create($mode, $id, $username, $seed)
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
		if($mode == 3)
		{
			$ausgang = ($id.$username);
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
		return sha1($session);
	}
	function seed_create()
	{
		$seed = $this->standardseed * rand(46,89);
		$rand = rand(111, 999);
		$seed += $rand;
		
		return $seed;
	}
}
?>