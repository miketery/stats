<?php
include('header.php');
$arr=array();
foreach($players as $pid=>$player)
   if($player!='Guest 1' and $player!='Guest 2')
      $arr[]=new player($pid,$player);

$arr=merge_sort($arr,'total_games');
echo "<div id=\"standings\">";
echo "<table class=\"radarclick\"><tr><th></th><th></th><th></th><th>".implode("</th><th>",$sports)."</th></tr>";
for($i=sizeof($arr)-1; $i>=0; $i--) {
   $arr[$i]->get_sports($sports);
   $arr[$i]->display_row();
   }

echo "</table>";

echo '<canvas id="radar_display" width="400" height="400">[No canvas support]</canvas>';
echo "</div>";

include('footer.php');
function get_player_sports($id,$sport_ids) {
   $arr=array();
   foreach($sport_ids as $sid=>$sport) {
      $sql="SELECT id FROM games WHERE sport_id=$sid AND FIND_IN_SET($id,`players`)";
      $tot=mysql_num_rows(mysql_query($sql));
      $sql="SELECT id FROM games WHERE sport_id=$sid AND FIND_IN_SET($id,`winners`)";
      $win=mysql_num_rows(mysql_query($sql));
      if($tot==0) $tot=1.5;
      $rat=round($win/$tot*100,1);
      if($tot==1.5) $arr[]=null;
      else $arr[]=$rat."%";
      }
   return $arr;
   }

class player {
   public $pid;
   public $display;
   public $total_games;
   public $stats;

   public function __construct($pid,$display) {
      $sql="SELECT COUNT(id) FROM games WHERE FIND_IN_SET($pid,`players`)";
      $this->total_games=array_pop(mysql_fetch_row(mysql_query($sql)));
      $sql="SELECT COUNT(id) FROM games WHERE FIND_IN_SET($pid,`winners`)";
      $this->won_games=array_pop(mysql_fetch_row(mysql_query($sql)));
      $this->ratio=round($this->won_games/$this->total_games*100,1);
      $this->pid=$pid;
      $this->display=$display;
      }

   public function get_sports($sports) {
      $sids=array_keys($sports);
      foreach($sids as $sid)
         $this->sport[$sid]=new sport($sid,$this->pid);
      }

   public function display_row() {
      echo "<tr><td>".$this->display."</td><td>".$this->total_games."</td><td>".$this->ratio."%</td>";
      foreach($this->sport as $s)
         echo "<td>$s->p_win</td>";
      echo "</tr>";
      }
}
class sport {
   public $sid;
   public $tot;
   public $win;
   public $loss;
   public $p_win;

   public function __construct($sid,$pid) {
      $sql="SELECT id FROM games WHERE sport_id=$sid AND FIND_IN_SET($pid,`players`)";
      $this->tot=mysql_num_rows(mysql_query($sql));
      $sql="SELECT id FROM games WHERE sport_id=$sid AND FIND_IN_SET($pid,`winners`)";
      $this->win=mysql_num_rows(mysql_query($sql));
      if($this->tot==0) { $this->p_win=""; $this->loss=0; return; }
      $this->p_win=round($this->win/$this->tot*100,0);
      }
   }

function merge_sort($classes,$by) {
   if(sizeof($classes)<=1) 
      return $classes;

   $left=array_slice($classes, 0, (int)(sizeof($classes)/2));
   $right=array_slice($classes, (int)(sizeof($classes)/2));

   $left=merge_sort($left,$by);
   $right=merge_sort($right,$by);

   return merge($left,$right,$by);
   }

function merge(&$left,&$right,$by) {
   $out=array();

   while(count($left)>0 or count($right)>0) {
      if(count($left)==0)
         array_push($out,array_shift($right));
      elseif(count($right)==0)
         array_push($out,array_shift($left));
      elseif($left[0]->$by>=$right[0]->$by)
         array_push($out,array_shift($right));
      else
         array_push($out,array_shift($left));
      }
   return $out;
   }
?>

    
    <script src="RGraph/libraries/RGraph.common.core.js" ></script>
    <script src="RGraph/libraries/RGraph.common.dynamic.js" ></script>
    <script src="RGraph/libraries/RGraph.common.tooltips.js" ></script>
    <script src="RGraph/libraries/RGraph.common.effects.js" ></script>
    <script src="RGraph/libraries/RGraph.radar.js" ></script>
    <script src="RGraph/libraries/RGraph.common.key.js" ></script>
    <!--[if lt IE 9]><script src="RGraph/excanvas/excanvas.js"></script><![endif]-->


    
    <script>
      $('table.radarclick tr:not(:first-child)').click(function() {
         $(this).hasClass('clicked') ? $(this).removeClass('clicked') : $(this).addClass('clicked');
         if($('tr.clicked').length==4) {$(this).removeClass('clicked'); return;}
         var sport=['<?php echo implode("','",$sports);?>'];
         var user_data=new Array();
         var user_info=new Array();
         var i=0;
         $('#standings table tr.clicked').each(function() {
           user_data[i]=new Array();
           var j=0;
           $(this).find('td').each(function() {
              if(j>2)
                 user_data[i].push($(this).text()/2);
              else
                 user_data[i].push($(this).text());
              j++;
           });
           user_info[i]=new Array();
           user_info[i][0]=user_data[i][0];
           user_info[i][2]=user_data[i][2];
           user_data[i].splice(0,3);
           i++;
         });
         console.log(i);
         if(i==0) { 
         var canvas = document.getElementById('radar_display');
         var ctx = canvas.getContext ("2d");
         ctx.clearRect(0,0,400,400);
            return;
         }
         if(i==1) {
            var radar = new RGraph.Radar('radar_display', user_data[0]);
            radar.Set('chart.key', [user_info[0][0]+" "+user_info[0][2]]);
            }
         else if(i==2){
            var radar = new RGraph.Radar('radar_display', user_data[0],user_data[1]);
            radar.Set('chart.key', [user_info[0][0]+" "+user_info[0][2],user_info[1][0]+" "+user_info[1][2]]);
            }
         else if(i==3){
            var radar = new RGraph.Radar('radar_display', user_data[0],user_data[1],user_data[2]);
            radar.Set('chart.key', [user_info[0][0]+" "+user_info[0][2],user_info[1][0]+" "+user_info[1][2],user_info[2][0]+" "+user_info[2][2]]);
            }
         radar.Set('chart.key.position', 'graph');
         radar.Set('chart.labels', sport);
         radar.Set('chart.background.circles.poly', true);
         radar.Set('chart.background.circles.spacing', 20);
         radar.Set('chart.colors', ['rgba(255,0,0,0.25)','rgba(0,255,0,0.25)','rgba(0,0,255,0.25)']);
         radar.Set('chart.axes.color', 'transparent');
         radar.Set('chart.highlights', true);
         radar.Set('chart.strokestyle', ['red','green','blue']);
         RGraph.Clear(radar.canvas);//ClearDraw(radar);
         radar.Draw();
      });
      </script>
