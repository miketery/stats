<?php
include("../mysql.php");
connect();
clean_var($_POST); 
clean_var($_GET);

$players=explode(",",chop($_POST["players"]));
$sports=explode(",",chop($_POST["sports"]));

$p_valid=array();
foreach($players as $player)
   if(is_numeric($player)) {
         if(exists("players","id",$player))
            $p_valid[]=$player;
         }
$s_valid=array();
foreach($sports as $sport)
   if(is_numeric($sport)) {
         if(exists("sports","id",$sport))
            $s_valid[]=$sport;
         }

$sql="SELECT id,display FROM players ORDER BY id";
$out=mysql_query($sql);
$players=array();
while($row=mysql_fetch_row($out))
   $players[$row[0]]=$row[1];

$sql="SELECT id,sport FROM sports";
$out=mysql_query($sql);
$sports=array();
while($row=mysql_fetch_row($out))
   $sports[$row[0]]=$row[1];

$where=array();
if(count($s_valid)>0)
   $where[]="s.id=".implode(" OR s.id=",$s_valid);
if(count($p_valid)>0)
   $where[]="FIND_IN_SET('".implode("',g.players) AND FIND_IN_SET('",$p_valid)."',g.players)";
if(count($where))
   $where="WHERE (".implode(") AND (",$where).")";
else
   $where=null;
if(count($p_valid)) {
   echo "<table>";
   foreach($p_valid as $p) {
      $tmp=new player($p,$p_valid,$s_valid,$where);
      echo "<tr><td>$players[$p]</td><td><span class=\"emphasis\">".$tmp->ratio."</span></td>
      <td>$tmp->wins</td><td>$tmp->losses
      <input type=\"hidden\" class=\"stat_players\" value=\"$p\"></td></tr>";
      }
   echo "</table>";
}

$sql="SELECT s.sport,g.players,g.winners,g.losers,g.score,g.date FROM games g LEFT JOIN sports s ON s.id=g.sport_id $where ORDER BY date desc, timestamp desc";
$out=mysql_query($sql);
$tot=mysql_num_rows($out);
$sql="SELECT s.sport,g.players,g.winners,g.losers,g.score,g.date FROM games g LEFT JOIN sports s ON s.id=g.sport_id $where ORDER BY date desc, timestamp desc LIMIT 0,60";
$out=mysql_query($sql);
$num=mysql_num_rows($out);
if($num==0) echo "Sorry no games found for requested query";
if($num>1 && $tot==$num) echo "Displaying <span class=\"emphasis\">$num</span> games";
elseif($num>1 && $tot>$num) echo "Displaying <span class=\"emphasis\">$num</span> out of <span class=\"emphasis\">$tot</span> games";

echo "<table>";
echo "<tr><th>Date</th><th>Game</th><th>Winners</th><th>Score</th><th>Losers</th></tr>";
while($row=mysql_fetch_row($out)) {
   $date=do_date($row[5]);
   echo "<tr><td>$date</td><td>".$row[0]."</td>";
   $tmp=array();
   $player_s="";
   foreach(explode(",",$row[2]) as $w)
      $tmp[]="<span class=\"pl$w\">$players[$w]</span>";
   echo "<td>".implode(", ",$tmp)."</td>";
   echo "<td>".$row[4]."</td>";
   $tmp=array();
   foreach(explode(",",$row[3]) as $l)
      $tmp[]="<span class=\"pl$l\">$players[$l]</span>";
   echo "<td>".implode(", ",$tmp)."</td></tr>";
   }
echo "</table>";


class player {
   public $pid;
   public $display;
   public $sports=array();
   public $other_players=array();
   public $ratio;
    
   public function __construct($pid, $other_players, $sports, $where=null) {
      $this->pid=$pid;
      $this->other_players=$other_players;
      $this->sports=$sports;
      $this->display=null;
      $this->process_stats($where);
      }

   public function process_stats($where) {
      //if(count($this->sports)>0)
       //  $where="AND (g.sport_id=".implode(" OR g.sport_id=",$this->sports).")";
      //else
      //   $where=null;
      if($where!=null)
         $where="AND ".substr($where, 6);
      $sql="SELECT g.id FROM games g LEFT JOIN sports s ON s.id=g.sport_id WHERE FIND_IN_SET($this->pid,g.`winners`) $where";
      $this->wins=mysql_num_rows(mysql_query($sql));
      $sql="SELECT g.id FROM games g LEFT JOIN sports s ON s.id=g.sport_id WHERE FIND_IN_SET($this->pid,g.`players`) $where";
      $this->total=mysql_num_rows(mysql_query($sql));
      if($this->total==0) {
         $this->ratio="0%";
         $this->losses=0;
         return;
         }
      $this->ratio=round($this->wins/$this->total*100,1)."%"; 
      $this->losses=$this->total-$this->wins;
      }
   }

function do_date($in) {
   $in=explode("-",$in);
   $d=$in[2]; $m=$in[1]; $y=$in[0];
   $year_today=date("Y",time());   	
   if($y!=$year_today) $y=", ".$year_today;
   else $y=null;
   $m=date("F",mktime(0, 0, 0, $m));
   return "$m $d$y";
	}

?>
