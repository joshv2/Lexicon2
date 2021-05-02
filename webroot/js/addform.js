$(function()
{

	$(':input',document.add_form).bind("change", function() { setConfirmUnload(true); });

	$('#add_form').submit(function() {
		$('input:submit').removeClass("blue").addClass("submitting").attr("disabled", true).val("Submitting ...");
		setConfirmUnload(false);
	});

	$("a.add").each(function() {
		$(this).siblings("input:last").addClass("multiplespid");
		$(this).siblings("input:last").addClass("multiplespsp");

		if ($(this).siblings(".multiple").size() > 1)
		{
			$(this).siblings("a.remove").removeClass("disabled");
		}
		//if ($(this).siblings(".multiple").size() > 10)
		//{
		//	$(this).addClass("disabled");
		//}
	});

	$("a.add").click(function() {
		var f = $(this).siblings(".multiple").size();
		$(this).siblings("a.remove").removeClass("disabled");
		//if (f < 10) {
		$(this).siblings(".multiple:last").clone().removeAttr("value").removeAttr("required").insertBefore(this);
			//if (f == 9)
			//{
			//	$(this).addClass("disabled");
			//}
		//}
	});

	$("a.remove").click(function() {
		var f = $(this).siblings(".multiple").size();
		$(this).siblings("a.add").removeClass("disabled");
		if ( f > 1 ) {
			$(this).siblings(".multiple:last").remove();
			if (f == 2) {
				$(this).addClass("disabled");
			}
		}
	});

});

function setConfirmUnload(on)
{
	 window.onbeforeunload = (on) ? unloadMessage : null;
}

function unloadMessage()
{
	return 'The data you entered will be lost.';
}