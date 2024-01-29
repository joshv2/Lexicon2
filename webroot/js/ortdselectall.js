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

        selectedOptions.forEach(value => {
            //console.log(value.split("_")[1]);
            if (!storedCheckboxData.hasOwnProperty(value)) {
                const wordIdsResponse = postDataSync(value);
                storedCheckboxData[JSON.stringify(value)] = wordIdsResponse.response.success.words
                //storedCheckboxData[value] = fetchWordIds(value, storedCheckboxData);
                localStorage.setItem('checkboxData', JSON.stringify(storedCheckboxData));
                
                
                //console.log(wordIds);
                //storedCheckboxData[value] = wordIds.success.words;
            }
          });

          for (const key in storedCheckboxData) {
            if (!selectedOptions.includes(key)) {
              delete storedCheckboxData[key];
            }
          }


        //console.log(2);
        //localStorage.setItem('checkboxData', JSON.stringify(storedCheckboxData));
        //console.log(JSON.parse(Object.values(storedCheckboxData)));

        console.log(Object.values(JSON.parse(localStorage.getItem('checkboxData'))).length);
        // Get the common word IDs across all keys in the checkboxData
        var commonWordIds = findCommonWordIds(Object.values(storedCheckboxData))
            //.filter(([key, value]) => value)
            //.map(([key]) => key)
        //)
        ;

        //console.log(commonWordIds);
        // Make AJAX call to fetch words based on common word IDs
        //fetchWords(commonWordIds);
    }

    function postDataSync(data) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'words/browsewords', false); // false makes the request synchronous
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrfToken"]').attr('content'));
      
        try {
          xhr.send(JSON.stringify({selectedOptions: data}));
      
          if (xhr.status === 200) {
            // Successful response
            var jsonResponse = JSON.parse(xhr.responseText);
            //console.log(jsonResponse);
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
    
    
    // Function to make AJAX call to fetch word IDs based on checkbox value
    async function fetchWordIds(checkboxValue, storedCheckboxData) {
        try {
            console.log(1);
            const response = await fetch('words/browsewords', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
            },
            body: JSON.stringify({selectedOptions: checkboxValue})
          })

          
          if (!response.ok) {
            throw new Error('Failed to fetch word IDs');
            
            }
        
        const wordIds = await response.json();
        const wordIds2 = await wordIds.response.success.words
        return wordIds2

    } catch (error) {
        console.error('Error fetching word IDs:', error);
    }
          /*.then(response => {
            if (response.ok) {
              return response.json();
            }
            throw new Error('Network response was not ok.');
          })
          .then(data => {
            storedCheckboxData[checkboxValue] = data.response.success.words
            localStorage.setItem('checkboxData', JSON.stringify(storedCheckboxData));
            console.log(2);
            })
          .catch(error => {
            console.error('Error:', error);
          });

          //return response.json();*/
    }

    // Function to make AJAX call to fetch words based on common word IDs
    function fetchWords(commonWordIds) {
        $.ajax({
            url: 'your_server_endpoint_for_words', // Replace with your server endpoint
            method: 'GET',
            data: {
                commonWordIds: commonWordIds
            },
            success: function(words) {
                // Display the fetched words
                console.log('Fetched Words:', words);
                // For demonstration purposes, just display the words
                $('#result').html('Fetched Words: ' + JSON.stringify(words));
            },
            error: function(error) {
                console.error('Error fetching words:', error);
            }
        });
    }

    // Function to get the common word IDs across all checkbox values
    function findCommonWordIds(lists) {
        // Implement your logic to find common word IDs based on checkbox values
        // For now, just return an empty array

        if (lists.length === 0) {
            return [];
        }
        console.log(3);
        // Initialize the result with the IDs from the first list
        let commonIds = lists[0].slice();
    
        // Iterate through the remaining lists
        for (let i = 1; i < lists.length; i++) {
            // Use filter to keep only the IDs present in both lists
            commonIds = Array.from(commonIds).filter(id => lists[i].includes(id));
        }
    
        return commonIds;
        //return [];
    }

    function clearLocal(){
        localStorage.setItem('checkboxData', JSON.stringify({}));
    }

    // Call the updateLocalStorageAndFilter function on button click
    $('#filterButton').on('click', updateLocalStorageAndFilter);

    // Trigger the function on page load
    updateLocalStorageAndFilter();