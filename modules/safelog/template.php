<body style="background: black; background-image: url(http://kasimir-blust.de/images/background_kblust_<?php echo rand(1, 2); ?>.jpg); color: white;"><center><?PHP
	
/*
 	************************************************************
 	Litotex Browsergame - Engine
 	http://www.Litotex.de
 	http://www.freebg.de

  Copyright (c) 2008 FreeBG Team
 	************************************************************
	Hinweis:
  Diese Software ist urheberrechtlich geschützt.

  Für jegliche Fehler oder Schäden, die durch diese Software
  auftreten könnten, übernimmt der Autor keine Haftung.
  
  Alle Copyright - Hinweise innerhalb dieser Datei 
  dürfen WEDER entfernt, NOCH verändert werden. 
  ************************************************************
  Released under the GNU General Public License 
  ************************************************************  

 */
 error_reporting(0);
 echo "
 <table>";
$count_all1 = 0;
$count_all2 = 0;
$bytes = 0;
 echo "Template cache operator 1.1.0 - EDITED by Kasimir Blust 2012<br><br>\n";
	if(!file_exists("./library/TemplateParser.php") && !file_exists("./library/class_template.php") && !is_dir("./lib") && !is_dir("./template/offline") && !is_dir("./cache/template")) {
		echo "Could not load all modules!";
		exit();
	}


 	require "./library/TemplateParser.php";

 	$TemplateParser = new TemplateParser("./cache/templates");

  	$dir = opendir("./templates");
   		while($file = readdir($dir)) {
		$i++;
		$count = 0;
      		if($file=="." || $file=="..") continue;
      		$inFile = @implode("",file("./templates/$file"));
      		$handle = $TemplateParser->Parse($inFile);
			$parsing = explode("*_*_#_#_9182#*#*#*#736455_#_#_*_*", $handle);
			$ParsedFile = $parsing[1];
			 $out1 = str_replace("Ä", "&Auml;", $ParsedFile);
			 $out1 = str_replace("Ö", "&Ouml;", $out1);
			 $out1 = str_replace("Ü", "&Uuml;", $out1);
			 $out1 = str_replace("ö", "&ouml;", $out1);
			 $out1 = str_replace("ä", "&auml;", $out1);
			 $out1 = str_replace("ß", "&szlig;", $out1);
			 $out1 = str_replace("§", "&sect;", $out1);
			 $ParsedFile = str_replace("ü", "&uuml;", $out1);
			$count = $parsing[0];
      		$noend = explode(".",$file);
      		$end = $noend[0];

      		$fp=@fopen("./cache/templates/1_".$end.".php","w");
      		$fw=@fwrite($fp,"<?PHP\n\n/**\nTemplatename: $end\n\nTemplatepackid: 1\n\nGenerate at ".date("H:i:s, d.m.Y")."\n\n**/\n\n\$template['$end']=\"".$ParsedFile."\";\n\n?>");

			$file_old = filesize("./templates/$file");
			if($file_old < 2)
				$file_old = filesize("./templates/$file");
			$file_new = filesize("./cache/templates/1_".$end.".php");
			if($file_new < 2)
				$file_new = filesize("./cache/templates/1_".$end.".php");
			
			$z[]="<tr><td>PARSING OFFLINE </td><td>cache/templates/<b>".$end."</b></td><td> WITH <b>$count</b> ACTIONS</td><td>&nbsp;&nbsp;&nbsp;&nbsp;<b>$file_old</b> Bytes</td><td> INTO <b>$file_new Bytes</b></td></tr>";
			$bytes += $file_new;
			$count_all1 += $count;
	  		fclose($fp);
      
      if(!$fp) {
      	print("Could not write into cache/template! -> $file OFFLINE");
      	exit();
      }

  }

 echo implode("\n",$z);
 
 echo "</table>";
 
 echo "<h1>100 % complete<br>".($count_all1+$count_all2)." ACTIONS<br>".str_replace(".", ",", (round(($bytes/1024), 2)))."KiloBytes(AVG: ".str_replace(".", ",", (round(($bytes / $i)/1024, 2)))."KBytes)</h1>";

?>
</center>