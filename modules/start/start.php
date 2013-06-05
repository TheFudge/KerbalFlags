<?php
// Admin Control Panel
// for official filmrausch Website at filmrausch.hdm-stuttgart.de
//
// Created and Developed by (c) Kasimir Blust, 2013
//
// File: script.php@start@modules
// Usage: Shown at the start of login

require_once "modules/templates/header.php";

?>
<h3>Welcome <?php echo $user['user']; ?>!</h3>
<p>Welcome back.</p>

<?php
require_once "modules/templates/footer.php";
?>