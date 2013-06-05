<?php
// If it's not set!
$debug = true;
$sqldebug = false;
function debug($type, $string)
{
	global $debug, $sqldebug;
	if($debug == true)
	{
		if($type=="sql" && $sqldebug === true)
		{
			echo "<code>$sqldebug $string</code><br/>";
		}
		else
		{
			echo "<pre>";
	        print_r($string);  
	    	echo "</pre><br/>";
    	}
	}
}

?>