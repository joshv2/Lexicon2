$(function() {
    $('[id^=play-pause-button-]').on("click", function() {
        alert(this.id);
        var curelementsplit = this.id.split("-");
        var playernumber = curelementsplit[3];
        console.log($(this).find(":first-child").hasClass('fa-volume-high'));
        if ($(this).first().hasClass('fa-volume-high')) {
            $(this).first().removeClass('fa-solid fa-volume-up');
            $(this).first().addClass('fa-solid fa-pause');
            console.log($("#audioplayer" + playernumber).get(0));
            $("#audioplayer" + playernumber).get(0).play();
        } else {
            $(this).children().eq(0).removeClass('fa-solid fa-pause');
            $(this).children().eq(0).addClass('fa-solid fa-volume-up');
            $("#audioplayer" + playernumber).get(0).pause();
        }


        $("#audioplayer" + playernumber).get(0).onended = function() {
            $("#play-pause-button-" + playernumber).first().removeClass('fa-solid fa-pause');
            $("#play-pause-button-" + playernumber).first().addClass('fa-solid fa-volume-up');
        };
    });

});