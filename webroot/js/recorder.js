(function(){
    // window.addEventListener('DOMContentLoaded', () => {
        // window.InitializeRecorder = function(recordBtn) {
            window.openRecorder = async (e, callback) => {
            // const getMic = document.getElementById('mic');
            // const recordButton = document.getElementById('record');
            // const recordButton = recordBtn;
            // const list = document.getElementById('recordings');
                if ('MediaRecorder' in window) {
                    // recordBtn.addEventListener('click', async (e) => {
                        // getMic.setAttribute('hidden', 'hidden');
                        e.preventDefault();
                        try {
                            const stream = await navigator.mediaDevices.getUserMedia({
                                audio: true,
                                video: false
                            });
                            const mimeType = 'audio/webm';
                            let chunks = [];
                            const recorder = new MediaRecorder(stream, { type: mimeType });
                            recorder.addEventListener('dataavailable', event => {
                                if (typeof event.data === 'undefined') return;
                                if (event.data.size === 0) return;
                                chunks.push(event.data);
                            });
                            recorder.addEventListener('stop', () => {
                                const recording = new Blob(chunks, {
                                type: mimeType
                                });
                                const list = document.getElementById('recordings');
                                renderRecording(recording, list);
                                chunks = [];
                            });
                            openRecordDialog(recorder, callback);
                            // recordButton.removeAttribute('hidden');
                            recordButton.addEventListener('click', () => {
                                // openRecordDialog(recorder);
                                // if (recorder.state === 'inactive') {
                                // recorder.start();
                                // recordButton.innerText = 'Stop';
                                // } else {
                                // recorder.stop();
                                // recordButton.innerText = 'Record';
                                // }
                            });
                        } catch {
                            renderError(
                                'You denied access to the microphone so this demo will not work.'
                            );
                        }
                    // });
                } else {
                    renderError(
                        "Sorry, your browser doesn't support the MediaRecorder API, so this demo will not work."
                    );
                }
            }
            
    //   });

      function renderError(message) {
        const main = document.getElementById('main');
        main.prepend(`<div class="error"><p>${message}</p></div>`);
      }

      function openRecordDialog(recorder, callback) {
          var recorderUI = buildRecorderUI(recorder);
          var configOpt = {
              title: "Record Word",
              message: "<div id='dialogContents' class='dialog-contents'><h3>Click the record button to begin recording.</h3><p>When you are done, click 'submit' to save your recording</p><ul id='recordings'></ul></div>",
              btnClassSuccessText: "Submit",
              btnClassFailText: "Cancel"
          }
          exConfirmPromise.make(configOpt).then((option) => {
            // alert(option);
            if (option)
                callback();
          });
          var dialogContents = document.getElementById('dialogContents');
          dialogContents.appendChild(recorderUI);
      }

      function buildRecorderUI(recorder) {
        var recordBtn = document.createElement("button");
        recordBtn.innerHTML = "Record";
        recordBtn.setAttribute('id', 'recordBtn');
        recordBtn.addEventListener('click', () => {
            if (recorder.state === 'inactive') {
                recorder.start();
                recordBtn.innerText = 'Stop';
            } else {
                recorder.stop();
                recordBtn.style.display = 'none';
            // recordBtn.innerText = 'Record another';
            }
        });
        return recordBtn;
      }

      function renderRecording(blob, list) {
        const blobUrl = URL.createObjectURL(blob);
        const li = document.createElement('li');
        const audio = document.createElement('audio');
        const anchor = document.createElement('a');
        const removeBtn = document.createElement('button');
        removeBtn.innerText = 'Re-record';
        removeBtn.addEventListener('click', () => {
            li.remove();
            showRecordBtn();
        });
        anchor.setAttribute('href', blobUrl);
        const now = new Date();
        // anchor.setAttribute(
        //   'download',
        //   `recording-${now.getFullYear()}-${(now.getMonth() + 1)
        //     .toString()
        //     .padStart(2, '0')}-${now
        //     .getDay()
        //     .toString()
        //     .padStart(2, '0')}--${now
        //     .getHours()
        //     .toString()
        //     .padStart(2, '0')}-${now
        //     .getMinutes()
        //     .toString()
        //     .padStart(2, '0')}-${now
        //     .getSeconds()
        //     .toString()
        //     .padStart(2, '0')}.webm`
        // );
        // anchor.innerText = 'Download';
        audio.setAttribute('src', blobUrl);
        audio.setAttribute('controls', 'controls');
        li.appendChild(audio);
        // li.appendChild(anchor);
        li.appendChild(removeBtn);
        list.appendChild(li);
      }

      function showRecordBtn() {
          var recordBtn = document.getElementById('recordBtn');
          recordBtn.style.display = "inline-block";
          recordBtn.click();
      }
}());