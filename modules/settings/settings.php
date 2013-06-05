<?php
// Admin Control Panel
// for official filmrausch Website at filmrausch.hdm-stuttgart.de
//
// Created and Developed by (c) Kasimir Blust, 2013
//
// File: script.php@start@modules
// Usage: Shown at the start of login

require_once "modules/templates/header.php";
$error = "";
if($option == "save")
{
	$db->query("UPDATE kerbanuser SET steam = '".mysql_real_escape_string($_POST['steam'])."', savename = '".mysql_real_escape_string($_POST['savename'])."', flag = '".mysql_real_escape_string($_POST['flag'])."' WHERE userID='$user[userID]'");
	$sql = $db->query("SELECT * FROM kerbanuser WHERE userID = '$user[userID]'");
	$user = $db->fetch_array($sql);
}
?>
<h2>Settings</h2>
<center>
<form action="secure.php?page=settings&option=save" method="post">
	<div class="hero-unit" style="width: 80%; margin: 5 auto; text-align: left;">
	<h3>SaveGame</h3>
			<p>
				The Updater needs to know the exact name of your SaveFile.<br/><br/>
				You can either start the game, klick on <code>Start Game</code> and select one of your SaveGames,<br/>
				or you can locate your SaveGames in the KSP Install Folder.<br/><br/>
				<input class="input-xlarge" type="text" name="savename" value="<?php echo $user['savename'] ?>"/>
			</p>
		</div>
		<div class="hero-unit" style="width: 80%; margin: 5 auto; text-align: left;">
			<h3>Steam Folder</h3>
			<p>
				The Update needs to know where your Steam folder is.<br/><br/>
				Normally it is either at<br/>
				<code>C:\Program Files\Steam</code><br/>
				or at<br/>
				<code>C:\Program Files (x86)\Steam</code>.<br/>
				If you didn't install Kerbal Space Program with Steam then locate the KSP Install Folder.<br/><br/>
				<input class="input-xxlarge" type="text" name="steam" value="<?php echo str_replace("\\\\", "\\", $user['steam']) ?>"/>
			</p>
		</div>
		<div class="hero-unit" style="width: 80%; margin: 5 auto; text-align: left;">
			<h3>Your Flag <img src=<?php 
			if(is_file("img/Flags/".$user['flag'].".png") == true)
				echo "\"img/Flags/".$user['flag'].".png\"";
			else
				echo "\"img/nodefaultflag.jpg\""

			?> style="width: 100px; border-radius: 5px; border: 1px solid grey;"/></h3>
			<p>
				To specify your flag for your company you have to tell the Updater the flags name.<br/><br/>
				All Flags are stored here<br/>
				<code>#KSP-Install-Folder#\GameData\Squad\Flags</code><br/>
				If you want your own special flag you can do that, but notice that your friends may not have these special non-default flags.<br/>
				Only type in the flag name not the file ending of the flag file.<br/>
				
					<input class="input-large" type="text" name="flag" value="<?php echo $user['flag'] ?>"/>
				</p>
		</div>
<input type="submit" class="btn btn-primary" value="Save Settings"/>
</form>
<?php
require_once "modules/templates/footer.php";
?>