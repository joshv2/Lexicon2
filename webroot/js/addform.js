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

		var hiddenInput = $(this).siblings('input[type="hidden"]').last();
		var counter = hiddenInput.attr('data-counter');
		var nextCounter = parseInt(counter) + 1;
		var hiddenInputClone = hiddenInput.clone();
		var hiddenInputName = hiddenInputClone.attr('name');
		var hiddenInputID = hiddenInputClone.attr('id');
		hiddenInputClone.attr('name', hiddenInputName.replace(counter, nextCounter));
		hiddenInputClone.attr('id', hiddenInputID.replace(counter, nextCounter));
		hiddenInputClone.attr('data-counter', nextCounter);
		hiddenInputClone.val('');
		var formGroup = $(hiddenInput).next();
		var formGroupClone = $(formGroup).clone();
		var label = $(formGroupClone).children('label');
		var labelFor = label.attr('for');
		label.attr('for', labelFor.replace(counter, nextCounter));
		var inputClone = $(label).next();
		inputCloneName = inputClone.attr('name');
		inputCloneID = inputClone.attr('id');
		inputClone.val('');
		inputClone.attr('name', inputCloneName.replace(counter, nextCounter));
		inputClone.attr('id', inputCloneID.replace(counter, nextCounter));
		hiddenInputClone.insertBefore(this);
		formGroupClone.insertBefore(this);
		inputClone.focus();

			//attr('name', 'alternates.1.id') f+1
			//if (f == 9)
			//{
			//	$(this).addClass("disabled");
			//}
		//}
	});

	$("a.remove").click(function() {
		var f = $(this).siblings(".muliplespid").length;
		$(this).siblings("a.add").removeClass("disabled");
		if ( f > 1 ) {
			var hiddenInput = $(this).siblings('input[type="hidden"]').last();
			var lastFormGroup = $(hiddenInput).next();
			$(hiddenInput).remove();
			$(lastFormGroup).remove();
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