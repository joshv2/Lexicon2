function toggleDropdown(dropdownId) {
    var checkboxes = document.getElementById("checkboxes" + dropdownId.slice(-1));
    checkboxes.classList.toggle("show");
  
    var dropdowns = document.getElementsByClassName("dropdown-content3");
    for (var i = 0; i < dropdowns.length; i++) {
      var otherDropdown = dropdowns[i];
      if (otherDropdown.id !== "checkboxes" + dropdownId.slice(-1)) {
        otherDropdown.classList.remove('show');
      }
    }
  }
  
  function checkboxChanged(checkbox) {
    var allCheckboxes = document.querySelectorAll('.checkboxesclass input[type="checkbox"]');
    //console.log(allCheckboxes);
    var selectedOptions = [];

    allCheckboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
        selectedOptions.push(checkbox.value);
        }
    });

    var jsonData = JSON.stringify({ selectedOptions: selectedOptions });
    console.log(jsonData);
    // Replace 'your_ajax_endpoint' with your actual endpoint URL
    makeAjaxCall(jsonData);
    
    
    
    /*console.log(checkbox);
    var dropdowns = document.getElementsByClassName("dropdown-content3 checkboxesclass");
    

    
    for (var i = 0; i < dropdowns.length; i++) {
      var checkboxes = dropdowns[i].getElementsByTagName("input");
      //console.log(checkboxes);
      for (var j = 3; j < checkboxes.length +3; j++) {
        console.log(checkboxes[j]);
        if (checkboxes[j] === checkbox) {
          console.log(checkboxes[j]);
          makeAjaxCall(checkbox.value, checkbox.checked);
        }
      }
    }*/
  }
  function makeAjaxCall(jsonData) {
    fetch('words/browsewords', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
        },
        body: jsonData
      })
      .then(response => {
        if (response.ok) {
          return response.json();
        }
        throw new Error('Network response was not ok.');
      })
      .then(data => {
        const elements = document.querySelectorAll('.word-list');

        // Loop through each element and remove its HTML content
        elements.forEach(element => {
            element.innerHTML = '';
        });
        console.log('Response:', data.response.success);
        const outputElement = document.getElementsByClassName('word-list');

        // Generate HTML and insert it into the output element
        outputElement[0].innerHTML = generateHTML(data);

        
      })
      .catch(error => {
        console.error('Error:', error);
      });
    
  }

  function get_translation(language_id){
    if (1 == language_id) {
      translation_strings = ['SEE FULL ENTRY', 'No words were found. Refine your search options above.'];
    } else if (2 == language_id){
      translation_strings = ['Veja informação completa', 'Nenhum verbete foi encontrado.'];
    } else if (3 == language_id) {
      translation_strings = ['Den ganzen Eintrag ansehen', 'Es wurden keine Wörter gefunden.'];
    } else {
      translation_strings = ['This language is not valid', 'This language is not valid'];
    }
    return translation_strings;
  }

  function generateHTML(data) {
    let wordresponse = JSON.parse(data.response.success.words);
    console.log(wordresponse.length);
    let html = '';
    if (wordresponse.length > 0) {
      html += '<ul class="word-list">';
      for(const item of wordresponse) {
          html += `<li class="group">
          <div class="word-main">
            <h3><a href='words/${item.id}'>${item.spelling}</a></h3>
            <a href='words/${item.id}' class='noborder'>${get_translation(data.response.success.language)[0]}<i class="fa fa-caret-down"></i></a>
          </div>
        </li>`;
      }
      html += '</ul>';
    } else {
      html += `<div class="c content">
			<p>${get_translation(data.response.success.language)[1]}</p>
		</div>`
    }

    return html;
  }
  
  // Close the dropdown only if the click is outside the dropdown area
  window.onclick = function(event) {
    var dropdowns = document.getElementsByClassName("dropdown-content3");
    for (var i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (!event.target.matches('.dropbtn3') && !openDropdown.contains(event.target)) {
        openDropdown.classList.remove('show');
      }
    }
  }