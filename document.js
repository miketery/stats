$(document).ready(function() {
   $(".pick_player").click(function () {
      sid=$(this).attr("value");
      uname=this.innerHTML;
      $(this).toggleClass("clicked");
      if($(this).hasClass("clicked")) {
         $(".picked_players").append('<input type="hidden" name="users[]" value="'+sid+'" class="pp'+sid+'">');
         }
      else {
         $(".pp"+sid).remove();
	$(this).css('background','#4AF');
	}
      process_q();
      });
   $(".pick_sport").click(function () {
      sid=$(this).attr("value");
      sport=this.innerHTML;
      if(!$(this).hasClass("clicked")) {
         $(".picked_sports input").remove();
         $(".pick_sport").removeClass('clicked');
         $(".picked_sports").append('<input type="hidden" name="sports[]" value="'+sid+'" class="ps'+sid+'">');
         }
      else 
         $(".ps"+sid).remove();
      $(this).toggleClass("clicked");
      process_q();
      });
   $(".formplayer").click(function (){
      uid=$(this).attr("value");
      player=this.innerHTML;
      $(this).toggleClass("clicked");
      if($(this).hasClass("clicked")) {
         $(this).append('<input type="hidden" name="players[]" value="'+uid+'" class="u'+uid+'">');
         $("#winners").append('<span class="winners u'+uid+' uplayer" value="'+uid+'">'+player+'</span>');
         }
      else
         $(".u"+uid).remove();
      });
   $(".winners").live("click", function (){
      uid=$(this).attr("value");
      $(this).toggleClass("clicked");
      if($(this).hasClass("clicked"))
         $(this).append('<input type="hidden" name="winners[]" value="'+uid+'" class="uw'+uid+'">');
      else
         $(".uw"+uid).remove();
      });
   $( "#datepicker" ).datepicker({dateFormat: "yy-mm-dd"});
// Dialog
      $('#addgame').dialog({
         autoOpen: false,
         width: 600,
         buttons: {
            "Add Game": function() {
			/////
			//ADD CODE HERE
               //$(this).find("form").submit();
               check_form();
            },
            "Cancel": function() {
               $(this).dialog("close");
            }
         }
      });
   $("#clickaddg").click(function() {
      $("#addgame").dialog('open');
      return false;
      }); 
   });

//$("#progressbar").progressbar({ value: 0 });
function check_form() {
   //Check SPORT SELECTED
   if($('#addgame form select[name=sport]').val()=="null")
      $('#addgame form select[name=sport]').parent().parent().addClass('redhigh'); 
   else
      $('#addgame form select[name=sport]').parent().parent().removeClass('redhigh'); 
   //CHECK PLAYERS SELECTED
   console.log($('#addgame form input[name="players[]"]').length);
   if($('#addgame form .formplayer input[name="players[]"]').length>1) 
      $('#addgame form .formplayer').parent().parent().removeClass('redhigh'); 
   else
      $('#addgame form .formplayer').parent().parent().addClass('redhigh'); 
   //CHECK WINNERS SELECTED   
   if($('#addgame form input[name="winners[]"]').length>0 && 
      $('#addgame form input[name="winners[]"]').length<$('#addgame form .formplayer input[name="players[]"]').length) 
      $('#addgame form #winners').parent().parent().removeClass('redhigh'); 
   else
      $('#addgame form #winners').parent().parent().addClass('redhigh'); 
   //there is aninput for password
   if($('#addgame form input[name="pass"]').val()=='')
      $('#addgame form input[name="pass"]').parent().parent().addClass('redhigh');
   else
      $('#addgame form input[name="pass"]').parent().parent().removeClass('redhigh');

   if($('tr.redhigh').length>0)
      $('#addgamemsg').html('Try Again!');
   else
      $('#addgame form').submit();
   }
function process_q() {
   //var ps = $('.picked_sports input');
   var ps='';
   var pp='';
   $(".picked_sports input").each(function() {
      ps=ps+$(this).attr("value")+","; 
      });
   $(".picked_players input").each(function() {
      pp=pp+$(this).attr("value")+","; 
      });
   var url="stats_query.php?s="+ps+"&p="+pp;
   $.post(
      'stats_query.php', {
      sports: ps,
      players: pp},
      function(data) {
         $("#display_stats").html(data);
         //$("#display_stats").append(data); 
	 colors=["#FFFF00","#7FFF00","#70FFFF","#FFB266","#CCF20D","#0AC2A3","#B28CD9","#FF6A6A"];
         $('.stat_players').each(function() {
            console.log($(this).attr('value'));
            $('.pl'+$(this).attr('value')).css('background',colors.pop());
            });
         }
      );
   }

process_q();
