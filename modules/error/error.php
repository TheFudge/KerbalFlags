<?php
// Admin Control Panel
// for official filmrausch Website at filmrausch.hdm-stuttgart.de
//
// Created and Developed by (c) Kasimir Blust, 2013
//
// File: script.php@error@modules
// Usage: Output a major error
// echo "ERROR: $error";
if(!isset($error))
{
	$error = "Ein unbekannter Fehler ist aufgetreten.";
}
require_once "modules/templates/header.php";
echo "<h4 class='text-error'>$error</h4>";
require_once "modules/templates/footer.php";

?>