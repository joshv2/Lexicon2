$(function()
{

	$(':input',document.add_form).bind("change", function() { setConfirmUnload(true); });

	$('#add_form').submit(function() {
		$('input:submit').removeClass("blue").addClass("submitting").attr("disabled", true).val("Submitting ...");
		setConfirmUnload(false);
	});

	$("a.add").each(function() {

		// if ($(this).siblings(".multiple").size() > 1)
		// {
		// 	$(this).siblings("a.remove").removeClass("disabled");
		// }
		//if ($(this).siblings(".multiple").size() > 10)
		//{
		//	$(this).addClass("disabled");
		//}
	});

	$("a.add").click(function() {
		var f = $(this).siblings(".multiple").length;
		$(this).siblings("a.remove").removeClass("disabled");
		//if (f < 10) {
		var input = $(this).siblings(".multiple:last").clone();
		var counter = parseInt(input.attr("data-counter"));
		input.attr("id", `alternates-${counter + 1}-spelling`);
		input.attr("data-counter", counter + 1);
		input.insertBefore(this);

			//attr('name', 'alternates.1.id') f+1
			//if (f == 9)
			//{
			//	$(this).addClass("disabled");
			//}
		//}
	});

	$("a.remove").click(function() {
		var f = $(this).siblings(".multiple").length;
		$(this).siblings("a.add").removeClass("disabled");
		if ( f > 1 ) {
			$(this).siblings(".multiple:last").remove();
			if (f == 2) {
				$(this).addClass("disabled");
			}
		}
	});

	$(".btn-record").click(openRecorderDialog);
	
	$('.remove-recording').click(function(e) {
		e.preventDefault();
		$('#recording-file').remove();
		$('.record-success').hide();
		$(".btn-record").show();
		$(this).hide();
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

function openRecorderDialog(e) {
	window.openRecorder(e, setRecording);
}

function setRecording(blob) {
	let file = new File([blob], "recording.webm",{type:"audio", lastModified:new Date().getTime()});
	let container = new DataTransfer();
	container.items.add(file);
	var fileInput = document.createElement("input");
	fileInput.type = "file";
	fileInput.id = "recording-file";
	$(fileInput).attr("name", "uploadedfile");
	$(fileInput).css("display", "none");
	fileInput.files = container.files;
	$('#add_form').prepend(fileInput);
	$('.remove-recording').show();
	$('.record-success').show();
	$(".btn-record").hide();
}