$(function()
{

	//$(':input',document.add_form).bind("change", function() { setConfirmUnload(true); });

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

	$("a.add-row").click(function() {
		addRow(this);
	});
	$("a.remove-row").click(function() {
		removeRow(this);
	});

	$("a.add-editor").click(function() {
		addEditorField(this, mappedEditors);
	});

	$("a.remove-editor").click(function() {
		removeEditorField(this, mappedEditors);
	});

	$(".btn-record").click(function(ev) {
		ev.preventDefault();
		openRecorderDialog(this);
	});
	
	$('.remove-recording').click(function(e) {
		e.preventDefault();
		$('#recording-file').remove();
		$('.record-success').hide();
		$(".btn-record").show();
		$(this).hide();
	});

	var editors = $('[id^=editor]');
	var mappedEditors = [];
	editors.each((index, el) => {
		  var quill = new Quill(el, {
			  theme: 'snow'
		  });
		  mappedEditors.push({"id": $(el).attr('id').replace('editor-', ''), "editor": quill});
	});
  
	$('#add_form').submit(function(e) {
		mappedEditors.forEach((el) => {
			var element = el.id;
			var stringifiedContent = JSON.stringify(el.editor.getContents());
			$(element).val(stringifiedContent);
		})
	  // $('#definition0').val(JSON.stringify(quill.getContents()));
	  return true;
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

function addRow(el) {
	$(el).siblings("a.remove-row").removeClass("disabled");
	let lastRow = $('.table-row').last();
	let counter = lastRow.attr('data-counter');
	let nextCounter = parseInt(counter) + 1;
	let newRow = lastRow.clone();
	let cells = newRow.children();
	cells.each((index, cell) => {
		let input = $(cell).find('input');
		if (input.length == 0)
			return;
		updateAttribute(input, 'name', counter, nextCounter);
		updateAttribute(input, 'id', counter, nextCounter);
		input.val('');
		let label = $(cell).find('label');
		if (label.length == 0)
			return;
		updateAttribute(label, 'for', counter, nextCounter);
	});
	let recordBtn = newRow.find('.btn-record');
	$(recordBtn).click(function(ev) {
		ev.preventDefault();
		openRecorderDialog(this)
	});
	$(recordBtn).text('Record');
	$(recordBtn).prev().hide(); //hide record success message
	$(recordBtn).next().find('input').files = '';
	newRow.attr('data-counter', nextCounter);
	lastRow.after(newRow);
}

function addEditorField(el, mappedEditors) {
	$(el).siblings("a.remove-editor").removeClass("disabled");
	var hiddenInput = $(el).siblings('input[type="hidden"]').last().prev();
	var counter = hiddenInput.attr('data-counter');
	var nextCounter = parseInt(counter) + 1;
	var hiddenInputClone = hiddenInput.clone();
	updateAttribute(hiddenInputClone, 'name', counter, nextCounter);
	updateAttribute(hiddenInputClone, 'id', counter, nextCounter);
	hiddenInputClone.attr('data-counter', nextCounter);
	hiddenInputClone.val('');
	var editorClone = getNewEditor(el, counter, nextCounter);
	var hiddenEditorInput = $(el).siblings('input[type="hidden"]').last();
	var hiddenEditorInputClone = hiddenEditorInput.clone();
	updateAttribute(hiddenEditorInputClone, 'name', counter, nextCounter);
	updateAttribute(hiddenEditorInputClone, 'id', counter, nextCounter);
	hiddenInputClone.insertBefore(el);
	hiddenEditorInputClone.insertBefore(el);
	var div = document.createElement('div');
	
	$(div).addClass('editor-container').append(editorClone);
	$(div).insertBefore(el);
	var quill = new Quill(editorClone[0], {
		theme: 'snow'
	});
	mappedEditors.push({"id": $(editorClone).attr('id').replace('editor-', ''), "editor": quill});
	quill.focus();
}

function removeRow(el) {
	var f = $('.table-row').length;
	let lastRow = $('.table-row').last();
	if ( f > 1 ) {
		lastRow.remove();
		if (f == 2) {
			$(el).addClass("disabled");
		}
	}
}

function removeEditorField(el, mappedEditors) {
	var f = $('.editor-container').length;
	let lastContainer = $('.editor-container').last();
	if ( f > 1 ) {
		const hiddenInputs = lastContainer.siblings('input[type="hidden"]');
		const last = hiddenInputs.last();
		const secondLast = hiddenInputs.last().prev();
		last.remove();
		secondLast.remove();
		lastContainer.remove();
		removeEditor(lastContainer, mappedEditors)
		if (f == 2) {
			$(el).addClass("disabled");
		}
	}
}

function removeEditor(container, mappedEditors) {
	const editor = container.children().last();
	const id = editor.attr('id').replace('editor-', '');
	removeByID(mappedEditors, 'id', id);
}

function removeByID(array, prop, value) {
	for(var i = 0; i < array.length; i++) {
		if(array[i][prop] == value){
			return array.splice(i,1);
		}
	}
}

function openRecorderDialog(el) {
	window.openRecorder(setRecording.bind(this, el));
}

function setRecording(recordBtn, blob) {
	let file = new File([blob], "recording.webm",{type:"audio/webm", lastModified:new Date().getTime()});
	let container = new DataTransfer();
	container.items.add(file);
	const inputEl = $(recordBtn).next().children('input')[0];
	inputEl.files = container.files;
	$('.remove-recording').show();
	$(recordBtn).prev('.record-success').show();
	$(".btn-record").text('Change');
}

function getNewEditor(el, counter, nextCounter) {
	var editor = $(el).siblings('.editor-container').last().children().last(); //get the editor div
	var editorClone = editor.clone();
	updateAttribute(editorClone, 'id', counter, nextCounter);
	return editorClone;
}

function getEditorToolbar() {
	return $('.ql-toolbar').last().clone();
}