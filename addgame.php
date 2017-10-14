<?php
//process add game
include("../mysql.php");
connect();
clean_var($_POST); 
clean_var($_GET);
if($_POST["pass"]!="243earl") {
	echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=index.php?e=9\">";
	die();
	}
//sport
$fields=explode(" ","sport players winners score");
foreach($fields as $f)
   $$f=$_POST[$f];
if(!is_array($winners))
   $winners=array($winners);
$errors=array();
$error[1]="Invalid sport";
$error[2]="Chose at least 2 existing players";
$error[3]="Chose at least 1 winnner";
$error[4]="All players cant be winners";
$error[5]="invalid date";
$error[6]="Invalid Score data entered";

if(exists("sports","id",$sport)==0)
   $errors[]=1;
if(exists("players","id",$players,1)!=count($players) or count($players)<2 or !is_array($players))
   $errors[]=2;
foreach($winners as $winner)
   if(!in_array($winner,$players))
      $errors[]=3;
if(count($winners)==count($players))
   $errors[]=4;

//$date=explode("-",$date);
//if(!checkdate($date[1],$date[2],$date[0]))
//   $errors[]=5;
//$date=implode("-",$date);
$date=date("Y-m-d",time());
if(trim($score)!='') {
   $score=preg_split("/[\s,-]+/", $score);
   if(count($score)!=2)
      $errors[]=6;
   elseif(!is_numeric($score[0]) or !is_numeric($score[1]))
      $errors[]=6;
   //elseif($score[0]==$score[1])
     // $errors[]=6;
   $score=implode("-",$score);
   }


if(count($errors)) {
   $errors=array_unique($errors);
   //redirect
//   echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=index.php?e=".implode("",$errors)."\">";
   die();
   }

//PROCESS
foreach($players as $player)
if(!in_array($player,$winners))
   $losers[]=$player;
$sql="INSERT INTO games (`sport_id`,`players`,`winners`,`losers`,`score`,`date`) ".
  "VALUES($sport,'".implode(",",$players)."','".implode(",",$winners)."','".implode(",",$losers)."','$score','$date')";
//echo "<br>$sql<br>";
mysql_query($sql);
echo "<META HTTP-EQUIV=\"Refresh\" Content=\"0; URL=index.php?s=1&sport=".$sport."\">";

?>
