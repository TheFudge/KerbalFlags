<center>
<h1>Intelligent Password Crypter - IPC</h1>
<h2>Passwörter angezeigt</h2>
<h3>
<ul>
<li>HansHerbert</li>
<li>huzg=8293?!0-wwd</li>
<li>Der beste Weg des Cryptoismuss</li>
</ul>
</h3>
<?php
$pw[1]= "HansHerbert";
$pw[2] = "huzg=8293?!0-wwd";
$pw[3] = "Der beste Weg des Cryptoismuss";

require "generator.php";

$pw_c[1] = IPC_generator_generate($pw[1], 0);
$pw_c[2] = IPC_generator_generate($pw[2], 0);
$pw_c[3] = IPC_generator_generate($pw[3], 0);

echo "<h2>Verschl&uuml;sselt -Mode 1</h2>";
echo "<h3>
<ul>
<li>$pw_c[1]</li>
<li>$pw_c[2]</li>
<li>$pw_c[3]</li>
</ul>
</h3>";


$pw_c[1] = IPC_generator_generate($pw[1], 256);
$pw_c[2] = IPC_generator_generate($pw[2], 264);
$pw_c[3] = IPC_generator_generate($pw[3], 294);

echo "<h2>Überprüfung -Mode 2</h2>";
echo "<h3>
<ul>
<li>$pw_c[1]</li>
<li>$pw_c[2]</li>
<li>$pw_c[3]</li>
</ul>
</h3>";

echo "<h2>Verschl&uuml;sselt (md5 - sh1)</h2>";
echo "<h3>
<ul>
<li>".md5($pw[1])." - ".sha1($pw[1])."</li>
<li>".md5($pw[2])." - ".sha1($pw[2])."</li>
<li>".md5($pw[3])." - ".sha1($pw[3])."</li>
</ul>
</h3>";

?>
<br>
<br>
<br>
<b>Session Crypter<br>
ID: 37 - USERNAME: TheFudge - SEED: 234<br>
<br>
CODE MODE 1:<br><br></b>
<?php
echo IPC_generator_session_create(1, 37, "TheFudge", 234);

echo "<br><br><b>CODE MODE: 2<br><br></b>";

echo IPC_generator_session_create(2, 37, "TheFudge", 234);
?>
</center>