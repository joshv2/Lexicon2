$(function() {
    $('[id^=play-pause-button-]').on("click", function() {
        alert(this.id);
        var curelementsplit = this.id.split("-");
        var playernumber = curelementsplit[3];
        console.log($(this).find(":first-child").hasClass('fa-volume-high'));
        if ($(this).find(":first-child").hasClass('fa-volume-high')) {
            $(this).find(":first-child").removeClass('fa-volume-high');
            $(this).find(":first-child").addClass('fa-pause');
            console.log($("#audioplayer" + playernumber).get(0));
            $("#audioplayer" + playernumber).get(0).play();
        } else {
            $(this).find(":first-child").removeClass('fa-pause');
            $(this).find(":first-child").addClass('fa-volume-high');
            $("#audioplayer" + playernumber).get(0).pause();
        }


        $("#audioplayer" + playernumber).get(0).onended = function() {
            $("#play-pause-button-" + playernumber).find(":first-child").removeClass('fa-pause');
            $("#play-pause-button-" + playernumber).find(":first-child").addClass('fa-volume-high');
        };
    });

});