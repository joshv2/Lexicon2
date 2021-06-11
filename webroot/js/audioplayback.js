$(function()
{
$('[id^=play-pause-button-]').on("click",function(){
   //alert(this.id);
    var curelementsplit = this.id.split("-");
   var playernumber = curelementsplit[3];
   if($(this).hasClass('fa-play'))
   {
     $(this).removeClass('fa-play');
     $(this).addClass('fa-pause');
     $("#audioplayer" + playernumber).get(0).play();
   }
  else
   {
     $(this).removeClass('fa-pause');
     $(this).addClass('fa-play');
     $("#audioplayer" + playernumber).get(0).pause();
   }


$("#audioplayer" + playernumber).get(0).onended = function() {
     $("#play-pause-button-" + playernumber).removeClass('fa-pause');
     $("#play-pause-button-" + playernumber).addClass('fa-play');
};
});

});