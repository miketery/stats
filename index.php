<?php
include('header.php');


echo "<div id=\"sports\">";
   //echo "<span class=\"reset\">Reset</span>";
   foreach($sports as $id=>$sport)
      echo "<span class=\"pick_sport\" value=\"$id\">$sport</span>"; 
   echo "<span class=\"picked_sports\"></span>"; //where hidden fields will get populated;
   echo "<div style=\"display: none\">";
   
   echo "</div>";

   echo "<div id=\"addsport\" title=\"Add New Sport\">";

   echo "</div>";
echo "</div><hr>";


echo "<div id=\"players\">";
   foreach($players as $id=>$player)
      if($player!='Guest 1' and $player!='Guest 2')
         echo "<span class=\"pick_player pl$id\" value=\"$id\">$player</span>"; 
   echo "<span class=\"picked_players\"></span>"; //where hidden fields will get populated;
   echo "<div class=\"addplayer\">";

   echo "</div>";
echo "</div><hr>";

echo "<div id=\"display_stats\"></div>";
include('footer.php');
?>
