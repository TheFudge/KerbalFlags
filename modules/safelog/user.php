<?php
require "kb_loader.php";
require "safelog/generator.php";

if($status == false)
{
	error("Timout","Du bist nicht mehr eingeloggt, bitte melde dich erneut an.");
	echo __LINE__ ." - ";
	exit;
}

if($action == "overview")
{
	if(isset($_GET['page'])) $page = $_GET['page'];
	else $page = "overview";
	if($page == "view")
	{
		$sql = $db->query("SELECT * FROM user_auftrag WHERE auftragID='".mysql_real_escape_string($_GET['id'])."'");
		if($db->num_rows($sql) == 0)
		{
			problem("Dieser Auftrag existiert nicht mehr.");
			$page = "overview";
		}
		else
		{
			$auftrag = $db->fetch_array($sql);
			
			$money = $auftrag['timesteps']." * 15 Minuten: ".($auftrag['timesteps'] * 3)."&euro;";
			
			$todo = str_replace("|", "<br>", $auftrag['todo']);
			$achievements = str_replace("|", "<br>", $auftrag['achievements']);
			
			$handle = explode("#", $auftrag['timeline']);
			$count = count($handle);
			$time = date("d.m.Y H:i", $auftrag['time']);
			$timeline = "<ul>";
			for($i = 1; $i < $count; $i++)
			{
				$text = $handle[$i];
				if(preg_match("/-->START/i", $text))
				{
					$timeline .= "<li>Block Start: ".str_replace("-->START", "", $text)."<br>
					<ul>";
				}
				elseif(preg_match("/-->END/i", $text))
				{
					$timeline .= "</ul><br>Block Ende: ".str_replace("-->END", "", $text)."<br>&nbsp;</li>";
				}
				else
				{
					$timeline .= "<li>$text</li>";
				}
			}
			$timeline .= "</li>";
			eval("\$tpl->output(\"".$tpl->get("online_overview_auftrag")."\");");
			exit;
		}
	}
	
	$page = "overview";
	if($page == "overview")
	{
		$user_domains = str_replace("|", "<br>", $user['domains']);
		$sql = $db->query("SELECT * FROM user_auftrag WHERE userID='$user[userID]'");
		$auftraege = "";
		while($auftrag = $db->fetch_array($sql))
		{
			// echo "hi";
			$auftraege .= "
			<tr>
				<td>$auftrag[name]</td>
				<td>$auftrag[auftragID]</td>
				<td>".date("d.m.Y H:i", $auftrag['time'])."</td>
				<td>$auftrag[status]</td>
				<td><a href=\"user.php$secure[sid]&action=overview&page=view&id=$auftrag[auftragID]\">zeigen</a></td>
			</tr>";
		}
		eval("\$tpl->output(\"".$tpl->get("online_overview")."\");");
		exit;
	}
}

eof();
?>