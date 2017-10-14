<!DOCTYPE html>
<html lang="en-US">
<head>
<link rel="stylesheet" href="style.css" type="text/css">
<script src="../js/jquery-1.8.2.min.js"></script>
<script src="../js/jquery-ui-1.8.24.custom.min.js"></script>
<link type="text/css" href="../ui-lightness/jquery-ui-1.8.24.custom.css" rel="stylesheet" />
</head>
<body>
<div id="header">
<h2>House Stats</h2>

<?php
include("../mysql.php");
connect();

$sql="SELECT id,sport FROM sports ORDER BY sport";
$out=mysql_query($sql);
$sports=array();
while($row=mysql_fetch_row($out))
   $sports[$row[0]]=$row[1];

$sql="SELECT id,display FROM players ORDER BY display";
$out=mysql_query($sql);
while($row=mysql_fetch_row($out))
   $players[$row[0]]=$row[1];

echo "<span id=\"clickaddg\" class=\"adding\">Add Game</span>";
//echo "<span class=\"adding\">Add Sport</span>";
//echo "<span class=\"adding\">Add Player</span>";
echo "<a href=\"index.php\"><span id=\"standings\" class=\"standings adding\">All Games</span></a>";
echo "<a href=\"standings.php\"><span id=\"standings\" class=\"standings adding\">Standings</span></a>";

if(isset($_GET["e"])) {
   echo "<span class=\"notice error\">"; 
   $e=str_split($_GET["e"]);
   if(in_array(9,$e)) echo "Incorrect Password! ";
   if(in_array(1,$e)) echo "Incorrect Sport! ";
   if(in_array(2,$e)) echo "Choose at least 2 players! ";
   if(in_array(3,$e)) echo "Choose at least 1 winner! ";
   if(in_array(4,$e)) echo "All players cant be winners! ";
   if(in_array(5,$e)) echo "Invalid Date! ";
   if(in_array(6,$e)) echo "Invalid score data entered! ";
   echo "</span>";
   }
if(isset($_GET["s"])) {
   echo "<span class=\"notice success\">"; 
   echo "Success adding game!";
   echo "</span>";
   }
echo "<div id=\"addgame\" title=\"Add Game\">";
   echo "<form method=\"POST\" action=\"addgame.php\">";
   echo "<table>";
   echo "<tr><td>Sport</td><td><select name=\"sport\">";
   echo "<option value=\"null\"></option>";
   foreach($sports as $id=>$sport)
      echo "<option value=\"$id\">$sport</option>";
   echo "</select></td></tr>";
   echo "<tr><td><div style=\"height: 6px; width: 10px;\"></div></td><td></td></tr>";
   echo "<tr><td>Players</td><td>";//.html_implode($players,"<span class=\"formplayer\">","</span>");
   foreach($players as $id=>$player)
      echo "<span class=\"formplayer uplayer\" value=\"$id\">$player</span>";
   echo "</td></tr>";
   echo "<tr><td><div style=\"height: 6px; width: 10px;\"></div></td><td></td></tr>";
   echo "<tr><td style=\"height: 40px;\">Winners</td><td><div id=\"winners\"></div></td></tr>";
   echo "<tr><td><div style=\"height: 6px; width: 10px;\"></div></td><td></td></tr>";
   echo "<tr><td>Score</td><td><input type=\"text\" name=\"score\" autocomplete=\"off\"></td></tr>";
   //echo "<tr><td>Date</td><td><input type=\"text\" name=\"date\" id=\"datepicker\" value=\"".date('Y-m-d',time())."\" autocomplete=\"off\"></td></tr>";
   echo "<tr><td>Password</td><td><input type=\"password\" name=\"pass\"><span id=\"addgamemsg\"></span></td></tr>";
   echo "</table>";
   echo "</form>";
echo "</div>"; //end FORM
echo "</div>"; //END id=header

?>

