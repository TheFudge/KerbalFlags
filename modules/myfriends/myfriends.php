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
?>
<h2>My Friends</h2>
<table style="padding: 5px; width: 99%">
	<tr>
		<td style="padding: 5px; width: 49%">
<?php

if($option == "accept")
{
	$sql = $db->query("SELECT fID FROM kerbanfriends WHERE fID='".mysql_real_escape_string($_GET['id'])."' AND answID='$user[userID]'");
	if($db->num_rows($sql) == 1)
	{
		$db->query("UPDATE kerbanfriends SET status = '1' WHERE fID='".mysql_real_escape_string($_GET['id'])."'");
	}
	else
		$error = "<div class=\"alert alert-error\">There was an error with the request.</div>";
	$option = "start";
}

if($option == "decline")
{
	$sql = $db->query("SELECT fID FROM kerbanfriends WHERE fID='".mysql_real_escape_string($_GET['id'])."' AND answID='$user[userID]'");
	if($db->num_rows($sql) == 1)
	{
		$db->query("UPDATE kerbanfriends SET status = '-1' WHERE fID='".mysql_real_escape_string($_GET['id'])."'");
	}
	else
		$error = "<div class=\"alert alert-error\">There was an error with the request.</div>";
	$option = "start";
}


if($option == "addFriend")
{
	$sql = $db->query("SELECT * FROM kerbanfriends WHERE (askID = '".mysql_real_escape_string($_GET['id'])."' OR answID = '".mysql_real_escape_string($_GET['id'])."' )AND (askID = '$user[userID]' OR answID = '$user[userID]') ");
	if($db->num_rows($sql) == 1)
	{
		$f = $db->fetch_array($sql);
		if($f['answID'] == $user['userID'])
			$error = "<div class=\"alert alert-error\">This User already asked about a relationship with you!</div>";
		elseif($f['status'] == 0)
		{
			$error = "<div class=\"alert alert-error\">You asked this User already! Answer pending...</div>";
		}
		elseif($f['status'] == 1)
		{
			$error = "<div class=\"alert alert-error\">You really forgot? That's is your friend already!</div>";
		}
		elseif($f['status'] == -1)
		{
			$error = "<div class=\"alert alert-error\">You asked this User already! He said no.</div>";
		}
		else
		{
			$error = "<div class=\"alert alert-error\">Unknown error. Please contact the admin imediately!</div>";
		}
	}
	else
	{
		if(is_numeric($_GET['id']))
		{
			$sql = $db->query("SELECT userID FROM kerbanuser WHERE userID='".mysql_real_escape_string($_GET['id'])."'");
			if($db->num_rows($sql) == 1)
			{
				$db->query("INSERT INTO  `kerbanfriends` (`fID` ,`askID` ,`answID` ,`status`)VALUES (NULL ,  '$user[userID]',  '".mysql_real_escape_string($_GET['id'])."',  '0');");
				$error = "<div class=\"alert alert-success\">Friend Request sent!</div>";
			}
				
			else
				$error = "<div class=\"alert alert-error\">User doesn't exist!</div>";
		}
	}
	$option = "start";
}

if($option == "start")
{
?>

			<h3>
				Add A Friend
			</h3>
			<?php echo $error; ?>
			<p>

				<ul class="list">
					<li class="text-error">With Facebook (soon)</li>
					<li class="text-error">With Google+ (soon)</li>
					<li>
						<form action="secure.php?page=myfriends&option=search" method="post">By Searching them on the website:<br/>
							<input name="searchstring" type="text" placeholder="Company/Short/Username" /><br/><input type="submit" class="btn btn-primary" value="Search"/>
						</form>
					</li>
				</ul>
			</p>
<?php
}
if($option == "search")
{

	?>
			<h3>
				Search A Friend
			</h3>
			<p>
				<ul class="list">
					<?php
					$sql = $db->query("SELECT userID, user, short, company FROM kerbanuser WHERE (user LIKE '%".mysql_real_escape_string($_POST['searchstring'])."%' OR company LIKE '%".mysql_real_escape_string($_POST['searchstring'])."%' OR short LIKE '%".mysql_real_escape_string($_POST['searchstring'])."%' ) AND userID != '$user[userID]'Limit 0,15");
					if($db->num_rows($sql) == 0)
					{
						echo "<li>Found nothing.<br/>
						<form action=\"secure.php?page=myfriends&option=search\" method=\"post\">Search again:<br/>
							<input name=\"searchstring\" value=\"".$_POST['searchstring']."\" type=\"text\" placeholder=\"Company/Short/Username\" /><br/><input type=\"submit\" class=\"btn btn-primary\" value=\"Search\" />
						</form></li>";
					}
					else
					{
						while($u = $db->fetch_array($sql))
						{
							echo "<li><a href=\"secure.php?page=myFriends&option=addFriend&id=$u[userID]\" title=\"Add $u[user] to your friends list\"><strong>$u[user]</strong></a>, Company: [{$u['short']}]$u[company]</li>";
						}
					}
					?>
				</ul>
			</p>
	<?php

}
?>
			<h3>
				Friendslist
			</h3>
				<?php

				$sql = $db->query("SELECT * FROM kerbanfriends WHERE status = '0' AND answID = '$user[userID]'");

				if($db->num_rows($sql) > 0)
				{
					?>
				<div class="alert alert-success">
				<h4>Some people want to be friends with you!</h4>
				<table class="table table-striped table-bordered alert-success">
				<tr><th>Name</th><th>Company</th><th>Short</th><th>Your Reaction?</th></tr>
					<?php
					while($f = $db->fetch_array($sql))
					{
						$sql2 = $db->query("SELECT userID, user, company, short FROM kerbanuser WHERE userID='$f[askID]'");
						$friend = $db->fetch_array($sql2);
						echo "
						<tr>
							<td>$friend[user]</td>
							<td>$friend[company]</td>
							<td>[{$friend['short']}]</td>
							<td><a href=\"secure.php?page=myFriends&option=accept&id=$f[fID]\" class=\"badge badge-success\" title=\"Accept Friend\"><i class=\"icon-ok icon-white\"></i></a>
							<a href=\"secure.php?page=myFriends&option=decline&id=$f[fID]\" class=\"badge badge-important\" title=\"Decline Friend\"><i class=\"icon-remove icon-white\"></i></a></td>
						</tr>";
						
					}

					?>
					</table>
				</div>
					<?php
					
				}
				else
				{
					echo "<p>No friend request to answer.</p>";
				}
				?>
			

			<h4>Your Friends</h4>
			<table class="table table-striped table-bordered">
				<tr><th>Name</th><th>Company</th><th>Short</th><th>FlagCount</th></tr>
				<?php

				$sql = $db->query("SELECT * FROM kerbanfriends WHERE status = '1' AND (askID = '$user[userID]' OR answID='$user[userID]')");


				if($db->num_rows($sql) == 0)
				{
					echo "<tr><td colspan='5' class='alert alert-error'>You are lonely and got no friends...</td></tr>";
				}
				else
				{
					while($f = $db->fetch_array($sql))
					{
						if($f['askID'] == $user['userID'])
							$uid = $f['answID'];
						else 	$uid = $f['askID'];
						$sql2 = $db->query("SELECT userID, user, company, short FROM kerbanuser WHERE userID='$uid'");
						$friend = $db->fetch_array($sql2);
						$sql2 = $db->query("SELECT COUNT(flagname) AS `counter` FROM kerbanflags WHERE userID = '".$friend['userID']."' AND official >= 0");
						$flag = $db->fetch_array($sql2);
						echo "
						<tr>
							<td>$friend[user]</td>
							<td>$friend[company]</td>
							<td>[{$friend['short']}]</td>
							<td>$flag[counter] flags</td>
						</tr>";
						
					}
					
				}
				?>
			</table>

			<h4>Your Requests</h4>
			<table class="table table-striped table-bordered">
				<tr><th>Name</th><th>Company</th><th>Short</th><th>FlagCount</th><th>Status</th></tr>
				<?php

				$sql = $db->query("SELECT * FROM kerbanfriends WHERE status != '1' AND askID = '$user[userID]' ORDER BY status DESC , fID DESC");

				if($db->num_rows($sql) == 0)
				{
					echo "<tr><td colspan='5' class='alert alert-error'>Do something! Add friends! Come on!</td></tr>";
				}
				else
				{
					while($f = $db->fetch_array($sql))
					{
						$sql2 = $db->query("SELECT user, company, short FROM kerbanuser WHERE userID='$f[answID]'");
						$friend = $db->fetch_array($sql2);
						$sql2 = $db->query("SELECT COUNT(flagname) AS `counter` FROM kerbanflags WHERE userID = '".$friend['userID']."' AND official = 1");
						$flag = $db->fetch_array($sql2);
						if($flag['counter'] == 0) $flags = "no public flags";
						else $flags = $flag['counter']." public flags";
						if($f['status'] == 0)
								$act = "<div title=\"Request still not answered\" class=\"badge badge-warning\"><i class=\"icon-question-sign icon-white\"></i></div>";
						else
							$act = "<div title=\"Request has been declined\" class=\"badge badge-important\"><i class=\"icon-minus-sign icon-white\"></i></div>";
						echo "
						<tr>
							<td>$friend[user]</td>
							<td>$friend[company]</td>
							<td>[{$friend['short']}]</td>
							<td>$flags</td>
							<td>$act</td>
						</tr>";
						
					}
					
				}
				?>
			</table>
		</td>
	</tr>
</table>
<?php
require_once "modules/templates/footer.php";
?>