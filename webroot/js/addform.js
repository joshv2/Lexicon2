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

	$("a.add-editor2").click(function() {
		addEditorField2(this, mappedEditors);
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

	var mappedEditors = initializeEditors();
	var newEditors2 = newEditors();
	console.log(newEditors2);
  
	$('#add_form').submit(function(e) {
		mappedEditors.forEach((el) => {
			var element = el.id;
			var stringifiedContent = JSON.stringify(el.editor.getContents());
			$('#' + element).val(stringifiedContent);
		})
	  // $('#definition0').val(JSON.stringify(quill.getContents()));
	  return true;
    });

	// show tooltip on click
	$("span[title]").click(function() {
		var $title = $(this).find(".title");
		if (!$title.length) {
		  $(this).append('<span class="title">' + $(this).attr("title") + '</span>');
		} else {
		  $title.remove();
		}
	});

	// automatically set initial pronunciation to match value of main spelling
	$('#spelling').blur(function() {
		var pronunciation = $('#pronunciations-0-spelling');
		if (pronunciation.val() == null || pronunciation.val()  == '')
			pronunciation.val($(this).val());
	});

	// Handle View More Sections
	$('.multiple-items').each((index, item) => {
		collapseMultipleItems(item);
	});

	$('.view-more-link').click(function(e) {
		e.preventDefault();
		showAllMultipleItems(this);
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

function initializeEditors() {
	var editors = $('[id^=editor]');
	var mappedEditors = [];
	editors.each((index, el) => {
		  var quill = new Quill(el, {
		    theme: 'snow',
		    modules: {
		      toolbar: getToolbarOptions()
		    }
		  });
		  mappedEditors.push({"id": $(el).attr('id').replace('editor-', ''), "editor": quill});
	});
	setInitialValues(mappedEditors);
	return mappedEditors;
}

function newEditors() {
	var newEditors = 0;
	return newEditors;
}

function getToolbarOptions() {
	return [[{ 'header': [1, 2, 3, false] }], ['bold', 'italic', 'underline', 'strike'], ['link'], ['clean']];
}

function setInitialValues(mappedEditors) {
	mappedEditors.forEach(editor => {
		const hiddenContent = $('#' + editor.id).val();
		if (hiddenContent) {
			var content = JSON.parse(hiddenContent);
			editor.editor.setContents(content.ops);
		}
	});
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

function addEditorField(el, mappedEditors, newEditors2) {
	$(el).siblings("a.remove-editor").removeClass("disabled");
	var hiddenInput = $('[id^=defeditor]').last();
	//console.log(hiddenInput.attr('data-counter'));
	var counter = hiddenInput.attr('data-counter');
	//console.log(counter);
	var nextCounter = parseInt(counter) + 1;
	
	var hiddenInputClone = hiddenInput.clone();
	//console.log($(el).closest('form-group'));
	$('#pronunciationsgroup').append(hiddenInputClone);

	//newBox = hiddenInputClone.children()[2];
	//$(newBox).children()[0].remove();

	var newQuill = $(hiddenInputClone.children()[2]);
	var newQuillUpdateId = $(newQuill.children()[1]);
	console.log(newQuillUpdateId);
	updateAttribute(newQuillUpdateId, 'id', counter, nextCounter);
	//console.log(newQuillUpdateId.attr('id'));

	var quill = new Quill('#' + newQuillUpdateId.attr('id'), {
		theme: 'snow',
		modules: {
			toolbar: getToolbarOptions()
		}
	});
	quill.setContents([]);
	mappedEditors.push({"id": newQuillUpdateId.attr('id').replace('editor-', ''), "editor": quill}); //
	console.log(newEditors2);
	newEditors2 += 1;
	quill.focus();
	
	updateAttribute($(hiddenInputClone.children()[0]), 'name', counter, nextCounter);
	updateAttribute($(hiddenInputClone.children()[0]), 'id', counter, nextCounter);
	$(hiddenInputClone.children()[0]).attr('data-counter', nextCounter);
	$(hiddenInputClone).attr('data-counter', nextCounter);
	$(hiddenInputClone.children()[0]).val('');
	$(hiddenInputClone.children()[1]).val('');
	$(hiddenInputClone.children()[3]).remove();
	//var editorClone = getNewEditor($(hiddenInputClone.children()[2]), counter, nextCounter);
	
	var hiddenEditorInput = $(hiddenInputClone.children()[1]);
	
	updateAttribute(hiddenEditorInput, 'id', counter, nextCounter);
	updateAttribute(hiddenEditorInput, 'name', counter, nextCounter);

	newBox = hiddenInputClone.children()[2];
	$(newBox).children()[0].remove();
	
}

function addEditorField2(el, mappedEditors, newEditors2) {
	$(el).siblings("a.remove-editor").removeClass("disabled");
	var hiddenInput = $('[id^=senteditor]').last();
	//console.log(hiddenInput.attr('data-counter'));
	var counter = hiddenInput.attr('data-counter');
	//console.log(counter);
	var nextCounter = parseInt(counter) + 1;
	
	var hiddenInputClone = hiddenInput.clone();
	//console.log($(el).closest('form-group'));
	$('#sentencesgroup').append(hiddenInputClone);

	//newBox = hiddenInputClone.children()[2];
	//$(newBox).children()[0].remove();

	var newQuill = $(hiddenInputClone.children()[2]);
	var newQuillUpdateId = $(newQuill.children()[1]);
	console.log(newQuillUpdateId);
	updateAttribute(newQuillUpdateId, 'id', counter, nextCounter);
	//console.log(newQuillUpdateId.attr('id'));

	var quill = new Quill('#' + newQuillUpdateId.attr('id'), {
		theme: 'snow',
		modules: {
			toolbar: getToolbarOptions()
		}
	});
	quill.setContents([]);
	mappedEditors.push({"id": newQuillUpdateId.attr('id').replace('editor-', ''), "editor": quill}); //
	console.log(newEditors2);
	newEditors2 += 1;
	quill.focus();
	
	updateAttribute($(hiddenInputClone.children()[0]), 'name', counter, nextCounter);
	updateAttribute($(hiddenInputClone.children()[0]), 'id', counter, nextCounter);
	$(hiddenInputClone.children()[0]).attr('data-counter', nextCounter);
	$(hiddenInputClone).attr('data-counter', nextCounter);
	$(hiddenInputClone.children()[0]).val('');
	$(hiddenInputClone.children()[1]).val('');
	$(hiddenInputClone.children()[3]).remove();
	//var editorClone = getNewEditor($(hiddenInputClone.children()[2]), counter, nextCounter);
	
	var hiddenEditorInput = $(hiddenInputClone.children()[1]);
	
	updateAttribute(hiddenEditorInput, 'id', counter, nextCounter);
	updateAttribute(hiddenEditorInput, 'name', counter, nextCounter);

	newBox = hiddenInputClone.children()[2];
	$(newBox).children()[0].remove();
	
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
	var editors = $(el).siblings('.editor-container');
	var f = editors.length;
	let lastContainer = editors.last();
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
	var editor = el; //get the editor div
	//console.log(editor);
	var editorClone = editor.clone();
	updateAttribute(editor, 'id', counter, nextCounter);
	return editorClone;
}

function getEditorToolbar() {
	return $('.ql-toolbar').last().clone();
}

function collapseMultipleItems(el) {
	var items = $(el).children();
	if (items.length > 4) {
		for (i = 3; i < items.length - 1; i++) {
			$(items[i]).slideUp();
		}
	}
}

function showAllMultipleItems(el) {
	var items = $(el).parent().children();
	for (i = 3; i < items.length - 1; i++) {
		$(items[i]).slideDown();
	}
	setTimeout(function() {
		$(items[items.length - 1]).hide()
	}, 300);
}