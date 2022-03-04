$(function() {
    $('[id^=play-pause-button-]').on("click", function() {
        //alert(this.id);
        var curelementsplit = this.id.split("-");
        var playernumber = curelementsplit[3];
        if ($(this).hasClass('fa-volume-up')) {
            $(this).removeClass('fa-volume-up');
            $(this).addClass('fa-pause');
            console.log($("#audioplayer" + playernumber).get(0));
            $("#audioplayer" + playernumber).get(0).play();
        } else {
            $(this).removeClass('fa-pause');
            $(this).addClass('fa-volume-up');
            $("#audioplayer" + playernumber).get(0).pause();
        }


        $("#audioplayer" + playernumber).get(0).onended = function() {
            $("#play-pause-button-" + playernumber).removeClass('fa-pause');
            $("#play-pause-button-" + playernumber).addClass('fa-volume-up');
        };
    });

});