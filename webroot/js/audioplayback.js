$(function() {
    $('[id^=play-pause-button-]').on("click", function() {
        //alert(this.id);
        var curelementsplit = this.id.split("-");
        var playernumber = curelementsplit[3];
        if ($(this).children[0].hasClass('fa-solid fa-volume-up')) {
            $(this).children[0].removeClass('fa-solid fa-volume-up');
            $(this).children[0].addClass('fa-solid fa-pause');
            console.log($("#audioplayer" + playernumber).get(0));
            $("#audioplayer" + playernumber).get(0).play();
        } else {
            $(this).children[0].removeClass('fa-solid fa-pause');
            $(this).children[0].addClass('fa-solid fa-volume-up');
            $("#audioplayer" + playernumber).get(0).pause();
        }


        $("#audioplayer" + playernumber).get(0).onended = function() {
            $("#play-pause-button-" + playernumber).children[0].removeClass('fa-solid fa-pause');
            $("#play-pause-button-" + playernumber).children[0].addClass('fa-solid fa-volume-up');
        };
    });

});