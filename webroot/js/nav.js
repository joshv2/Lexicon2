$(document).ready(function()
{
	$(".submenu").each(function() {
		var width = $(this).parent().width().toString();
		$(this).css({"min-width": width + "px"});
	});

	$(".dropdown .main").click(function()
	{
		var current = $(this).siblings(".submenu");
		$(".submenu.open").not(current).removeClass("open").fadeOut("fast");
		$(".dropdown .main").removeClass("active");

		if (current.hasClass("open"))
		{
			current.removeClass("open").fadeOut("fast");
		}
		else
		{
			$(this).addClass("active");
			current.addClass("open").fadeIn("fast");
		}
	});

	$(".submenu").mouseup(function(){return false});
	$(".dropdown .main").mouseup(function(){return false});

	$(document).mouseup(function() {
		$(".dropdown .main").removeClass("active");
		$(".submenu.open").removeClass("open").fadeOut("fast");
	});
});
