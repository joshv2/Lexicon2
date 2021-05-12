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
		$(this).siblings("a.remove").removeClass("disabled");
		var hiddenInput = $(this).siblings('input[type="hidden"]').last();
		var counter = hiddenInput.attr('data-counter');
		var nextCounter = parseInt(counter) + 1;
		var hiddenInputClone = hiddenInput.clone();
		updateAttribute(hiddenInputClone, 'name', counter, nextCounter);
		updateAttribute(hiddenInputClone, 'id', counter, nextCounter);
		hiddenInputClone.attr('data-counter', nextCounter);
		hiddenInputClone.val('');
		var formGroup = $(hiddenInput).next();
		var formGroupClone = $(formGroup).clone();
		var label = $(formGroupClone).children('label');
		updateAttribute(label, 'for', counter, nextCounter);
		var inputClone = $(label).next();
		updateAttribute(inputClone, 'name', counter, nextCounter);
		updateAttribute(inputClone, 'id', counter, nextCounter);
		inputClone.val('');
		hiddenInputClone.insertBefore(this);
		formGroupClone.insertBefore(this);
		inputClone.focus();
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

function updateAttribute(el, attribute, counter, nextCounter) {
	var attrValue = el.attr(attribute);
	el.attr(attribute, attrValue.replace(counter, nextCounter));
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