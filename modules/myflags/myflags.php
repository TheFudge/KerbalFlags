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
function officials($int)
{

	if($int == 0)
		return "alert alert-warning";
	if($int == 1)
		return "alert alert-success";
	if($int == -1)
		return "alert alert-error";
}
function officials2($int)
{

	if($int == 0)
		return "text-warning";
	if($int == 1)
		return "text-success";
	if($int == -1)
		return "text-error";
}
function officials_white($int, $b)
{

	if($int == $b)
		return " icon-white";
}

if($option == "viewown")
{
	$sql = $db->query("SELECT * FROM kerbanflags WHERE flagID = '".mysql_real_escape_string($_GET['id'])."'");
	if($db->num_rows($sql) != 1)
	{
		$error = "<div class=\"alert alert-error\">Selected flag doesn't exist.</div>";
		$option = "start";
	}
	else
	{
		$flag = $db->fetch_array($sql);
		if($flag['userID'] == $user['userID'])
		{
			$desc = explode("PlaqueText = ", $flag['text']);
			debug("var", $desc);
			$desc = explode("\r\n", $desc[1]);
			debug("var", $desc);
			$desc = $desc[0];
			debug("var", $desc);
			?>
			<h2 class="<?php echo officials($flag['official']) ?>">My Flag - <?php echo $flag['flagname']; ?></h2>
			
			<p>
				<h3>General Information</h3>
				<table class="table table-striped table-bordered" style="width: 50%;">
					<tr>
						<th>Company</th>
						<td>Your Flag</td>
					</tr>
					<tr>
						<th>Description</th>
						<td><?php echo $desc; ?></td>
					</tr>
					<tr>
						<th>Position</th>
						<td>unknown</td>
					</tr>
				</table>
			</p>
			<?php


		}
		else
		{

		}
	}
}
if($option != "viewown")
{
	if($option == "change")
	{
		$sql = $db->query("SELECT * FROM kerbanflags WHERE flagID = '".mysql_real_escape_string($_GET['id'])."' AND userID = '$user[userID]'");
		if($db->num_rows($sql) != 1)
		{
			$error = "<div class=\"alert alert-error\">Flag doesn't exist or is not your flag!</div>";
		}
		else
		{
			if($step == "n")
			{
				// INVISIBLE
				$db->query("UPDATE kerbanflags SET official = 0-1 WHERE flagID = '".mysql_real_escape_string($_GET['id'])."' AND userID = '$user[userID]'");
			}
			elseif($step == "f")
			{
				// FRIENDS
				$db->query("UPDATE kerbanflags SET official = 0 WHERE flagID = '".mysql_real_escape_string($_GET['id'])."' AND userID = '$user[userID]'");
			}
			elseif($step == "e")
			{
				// EVERYONE
				$db->query("UPDATE kerbanflags SET official = 1 WHERE flagID = '".mysql_real_escape_string($_GET['id'])."' AND userID = '$user[userID]'");
			}
			else
			{
				$error = "<div class=\"alert alert-error\">Some data is false. Please try again. #{$step}#</div>";
			}
		}
	}

	?>
	<h2>My Flags</h2>
	<?php echo $error; ?>
	<table style="padding: 5px; width: 99%">
		<tr>
			<td style="padding: 5px; width: 49%; vertical-align: top;">
				<p>
				This is a List of Your uploaded flags by the FlagUpdater.
				</p>
				<table class="table table-bordered">
					<tr><th>FlagPid</th><th>Flag Name</th><th title="Status indicates wheter that Flag is visible to Friends Everyone or Nobody.">Status</th></tr>
					<?php
					$sql = $db->query("SELECT flagID, pid, flagname, official  FROM kerbanflags WHERE userID = '$user[userID]'");
					while($flag = $db->fetch_Array($sql))
					{
						echo "
						<tr>
							<td class=\"".officials($flag['official'])."\">$flag[pid]</td>
							<td class=\"".officials($flag['official'])."\"><a href=\"secure.php?page=myflags&option=viewown&id=$flag[flagID]\"><span class=\"".officials2($flag['official'])."\">$flag[flagname]</span></a></td>
							<td class=\"".officials($flag['official'])."\">
							<a href=\"secure.php?page=myflags&option=change&step=e&id=$flag[flagID]\" class=\"badge badge-success\" title=\"Visible for Everyone\"><i class=\"icon-plus-sign".officials_white($flag['official'],1). "\"></i></a>
							<a href=\"secure.php?page=myflags&option=change&step=f&id=$flag[flagID]\" class=\"badge badge-warning\" title=\"Visible only for friends\"><i class=\"icon-minus-sign". officials_white($flag['official'] , 0) . "\"></i></a>
							<a href=\"secure.php?page=myflags&option=change&step=n&id=$flag[flagID]\" class=\"badge badge-important\" title=\"Make it invisible\"><i class=\"icon-remove-sign". officials_white($flag['official'] , -1) . "\"></i></a>
							</td>
						</tr>";
					}
					?>
				</table>
			</td>
			<td style="padding: 5px; vertical-align: top;">
				<p>
				These Flags are the ones of your Friends that the FlagUpdater will add to your game.
				</p>
					<?php
					$sql = $db->query("SELECT * FROM kerbanfriends WHERE status = '1' AND (askID = '$user[userID]' OR answID='$user[userID]')");


					if($db->num_rows($sql) == 0)
					{
						echo "<tr><td colspan='5' class='alert alert-error'>You are lonely and got no friends...</td></tr>";
					}
					else
					{
						?>
					<table class="table table-striped table-bordered">
					<tr><th>FlagPid</th><th>Flag Name</th><th title="Status indicates wheter that Flag is visible to Friends Everyone or Nobody.">Status</th></tr>
						<?php

						while($f = $db->fetch_array($sql))
						{
							if($f['askID'] == $user['userID'])
								$uid = $f['answID'];
							else 	$uid = $f['askID'];
							$sql2 = $db->query("SELECT userID, user, company, short FROM kerbanuser WHERE userID='$uid'");
							$friend = $db->fetch_array($sql2);
							$sql3 = $db->query("SELECT flagID, flagname  FROM kerbanflags WHERE userID = '$uid' AND official >= 0");
							while($flag = $db->fetch_Array($sql3))
							{
								echo "
								<tr>
									<td>$friend[company]</td>
									<td>$friend[user]</td>
									<td><strong><a href=\"secure.php?page=myflags&option=viewother&id=$flag[flagID]\"><span class=\"".officials2($flag['official'])."\">$flag[flagname]</span></a></strong></td>
								</tr>";
							}
							
						}
						
						?>
						</table>
						<?php
					}
					?>
			</td>
		</tr>
	</table>
	<?php
}
require_once "modules/templates/footer.php";
?>