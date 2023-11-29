$(document).ready(function() {
			
			$('#home2_filters li h4').click(function() {
				$('#home2_filters li h4').removeClass('on');
				$('#home2_filters li ul').slideUp('normal');
				if($(this).next().is(':hidden') == true) {
					$(this).addClass('on');
					$(this).next().slideDown('normal');
				}
			});
			$('#home2_filters li ul').hide();

		});


function myFunction() {
	var x = document.getElementById("myTopnav");
	if (x.className === "topnav") {
		x.className += " responsive";
	} else {
		x.className = "topnav";
	}
	}