window.onload = clearLocal()

var storedCheckboxData = JSON.parse(localStorage.getItem('checkboxData')) || {};

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


  function checkboxChanged(checkbox) {
    var allCheckboxes = document.querySelectorAll('.checkboxesclass input[type="checkbox"]');
    //console.log(allCheckboxes);
    var selectedOptions = [];
    var selectedOptionsDesc = [] // for the description of what is checked
    allCheckboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
        selectedOptions.push(checkbox.value);
        selectedOptionsDesc.push(checkbox.parentElement.textContent);
        }
    });

    var jsonData = JSON.stringify({ selectedOptions: selectedOptions });
    
    console.log(selectedOptions);

    // Update the content of the separate div
    updateCheckedOptionsDiv(selectedOptionsDesc)
    // Replace 'your_ajax_endpoint' with your actual endpoint URL
    updateLocalStorageAndFilter(selectedOptions, storedCheckboxData);
  

  }


  function updateCheckedOptionsDiv(selectedOptions) {
    var checkedOptionsDiv = document.getElementById('checkedOptionsDiv');
    checkedOptionsDiv.innerHTML = selectedOptions.length > 0 ? 'Checked Options: ' + selectedOptions.join(', ') : '';
  }

  
    // Function to update localStorage and perform filtering
    function updateLocalStorageAndFilter(selectedOptions, storedCheckboxData) {
        // Get the current checked values
        var currentCheckboxData = {};
        if (typeof selectedOptions === 'undefined') {
            // If selectedOptions is undefined, initialize it as an empty array
            var selectedOptions = [];
        }
        console.log(selectedOptions);
        selectedOptions.forEach(value => {
            console.log(value);
            if (!storedCheckboxData.hasOwnProperty(value)) {
                //console.log(wordIdsResponse.response.success.words);
                const wordIdsResponse = postDataSync1(value);
                storedCheckboxData[JSON.stringify(value)] = wordIdsResponse.success.words //response.
                
                localStorage.setItem('checkboxData', JSON.stringify(storedCheckboxData));
            }
          });

          for (const key in storedCheckboxData) {
            if (!selectedOptions.includes(key)) {
              delete storedCheckboxData[key];
            }
          }


        console.log(Object.values(JSON.parse(localStorage.getItem('checkboxData'))).length);
        // Get the common word IDs across all keys in the checkboxData
        const commonWordIds = findCommonValues(Object.values(JSON.parse(localStorage.getItem('checkboxData'))))        ;

        console.log(commonWordIds);
        // Make AJAX call to fetch words based on common word IDs
        makeAjaxCall(JSON.stringify({'requestedWordIds' : commonWordIds}));
    }

    function postDataSync1(data) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'words/browsewords', false); // false makes the request synchronous
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrfToken"]').attr('content'));
      
        try {
          console.log(JSON.stringify({selectedOptions: data}));
          xhr.send(JSON.stringify({selectedOptions: data}));
          if (xhr.status === 200) {
            // Successful response
            var jsonResponse = JSON.parse(xhr.responseText);
            console.log("pds1:" + jsonResponse);
            return jsonResponse;
          } else {
            // Handle error
            console.error('Error:', xhr.status, xhr.statusText);
            throw new Error(`Error: ${xhr.status} - ${xhr.statusText}`);
          }
        } catch (error) {
          console.error('Error:', error.message);
          throw error; // You can choose to handle or rethrow the error as needed
        }
      }

    function findCommonValues(jsonStrings) {
        // Create an array of sets to store unique values for each array
        
        const arrays = jsonStrings.map(jsonString => JSON.parse(jsonString));
        console.log(arrays);

        if (arrays.length === 1) {
            return arrays[0].map(obj => obj.id);
          }

        const sets = arrays.map(array => new Set(array.map(obj => obj.id)));
      
        // Find the intersection of all sets
        const commonValues = sets.reduce((intersection, currentSet) => {
          // Check if intersection is empty (first iteration)
          if (intersection.size === 0) {
              // Return currentSet as the initial value
              return currentSet;
          } else {
              // Return the intersection of intersection and currentSet
              return new Set([...intersection].filter(value => currentSet.has(value)));
          }
      }, new Set());

        /*const commonValues = sets.reduce((intersection, currentSet) => {
          return new Set([...intersection].filter(value => currentSet.has(value)));
        });*/
      
        // Convert the set to an array if needed
        const commonValuesArray = Array.from(commonValues);
      
        return commonValuesArray;
      }
    
      function makeAjaxCall(jsonData) {
        fetch('words/browsewords2', {
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
    
            const element = document.getElementById('paging_info');
    
            // Check if the element exists before manipulating it
            if (element) {
              // Set the innerHTML to an empty string to remove its content
              element.innerHTML = 'Words returned: ' + JSON.parse(data.success.words).length;
            }
    
            const elements2 = document.querySelectorAll('.pagination');
    
            // Loop through each element and remove its HTML content
            elements2.forEach(element => {
                element.innerHTML = '';
            });
            //console.log('Response:', data.response.success);
            const outputElement = document.getElementsByClassName('word-list');
    
            // Generate HTML and insert it into the output element
            outputElement[0].innerHTML = generateHTML(data);
    
            
          })
          .catch(error => {
            console.error('Error:', error);
          });
        
      }

    function clearLocal(){
        localStorage.setItem('checkboxData', JSON.stringify({}));
    }

    function get_translation(language_id){
        const languageIdTranslations = {
          1: ['SEE FULL ENTRY', 'No words were found. Refine your search options above.'],
          2: ['Veja informação completa', 'Nenhum verbete foi encontrado.'],
          3: ['Den ganzen Eintrag ansehen', 'Es wurden keine Wörter gefunden.'],
          default: ['This language is not valid', 'This language is not valid']
        };
        
        const keys = Object.keys(languageIdTranslations);
    
        if (keys.includes(language_id.toString())) {
          return languageIdTranslations[language_id];
        } else {
          return languageIdTranslations['default'];
        }
      }
    
      function generateHTML(data) {
        let wordresponse = JSON.parse(data.success.words);
        console.log(wordresponse.length);
        let html = '';
        if (wordresponse.length > 0) {
          html += '<ul class="word-list">';
          for(const item of wordresponse) {
              html += `<li class="group">
              <div class="word-main">
                <h3><a href='words/${item.id}'>${item.spelling}</a></h3>
                <a href='words/${item.id}' class='noborder'>${get_translation(data.success.language)[0]}<i class="fa fa-caret-down"></i></a>
              </div>
            </li>`;
          }
          html += '</ul>';
        } else {
          html += `<div class="c content">
                <p>${get_translation(data.success.language)[1]}</p>
            </div>`
        }
    
        return html;
      }
    // Call the updateLocalStorageAndFilter function on button click
    $('#filterButton').on('click', updateLocalStorageAndFilter);

    // Trigger the function on page load
    //updateLocalStorageAndFilter();