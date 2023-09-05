$(function() {
    $('[id^=play-pause-button-]').on("click", function() {
        //alert(this.id);
        var curelementsplit = this.id.split("-");
        var playernumber = curelementsplit[3];
        if ($(this).hasClass('fa-solid fa-volume-up')) {
            $(this).removeClass('fa-solid fa-volume-up');
            $(this).addClass('fa-solid fa-pause');
            console.log($("#audioplayer" + playernumber).get(0));
            $("#audioplayer" + playernumber).get(0).play();
        } else {
            $(this).removeClass('fa-solid fa-pause');
            $(this).addClass('fa-solid fa-volume-up');
            $("#audioplayer" + playernumber).get(0).pause();
        }


        $("#audioplayer" + playernumber).get(0).onended = function() {
            $("#play-pause-button-" + playernumber).removeClass('fa-solid fa-pause');
            $("#play-pause-button-" + playernumber).addClass('fa-solid fa-volume-up');
        };
    });

});