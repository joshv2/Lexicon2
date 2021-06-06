(function(){
    var theBlob;
    var theStream;
    // window.addEventListener('DOMContentLoaded', () => {
        // window.InitializeRecorder = function(recordBtn) {
            window.openRecorder = async (callback) => {
            // const getMic = document.getElementById('mic');
            // const recordButton = document.getElementById('record');
            // const recordButton = recordBtn;
            // const list = document.getElementById('recordings');
                if ('MediaRecorder' in window) {
                    // recordBtn.addEventListener('click', async (e) => {
                        // getMic.setAttribute('hidden', 'hidden');
                        try {
                             theStream = await navigator.mediaDevices.getUserMedia({
                                audio: true,
                                video: false
                            });
                            const mimeType = 'audio/webm';
                            let chunks = [];
                            const recorder = new MediaRecorder(theStream, { type: mimeType });
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
                        } catch {
                            renderError(
                                'You denied access to the microphone so this feature will not work.'
                            );
                        }
                    // });
                } else {
                    renderError(
                        "Sorry, your browser doesn't support the MediaRecorder API, so this feature will not work."
                    );
                }
            }
            
    //   });

      function renderError(message) {
        const main = document.getElementById('main');
        const div = document.createElement('div');
        div.classList = 'error';
        div.innerHTML = message;
        main.prepend(div);
      }

      function openRecordDialog(recorder, callback) {
          var recorderUI = buildRecorderUI(recorder);
          var configOpt = {
              title: "Record Word",
              message: "<div id='dialogContents' class='dialog-contents'><h3>Click the record button to begin recording.</h3><p>When you are done, click 'Stop', 'Save' and then 'Submit' to save your recording</p><div id='recordings'></div></div>",
              btnClassSuccessText: "Save",
              btnClassFailText: "Cancel"
          }
          exConfirmPromise.make(configOpt).then((option) => {
            theStream.getTracks().forEach(track => track.stop());
            setTimeout(() => {
                if (option) {
                    callback(theBlob);
                }
            }, 10);
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
            }
        });
        return recordBtn;
      }

      function renderRecording(blob, list) {
        theBlob = blob;
        const blobUrl = URL.createObjectURL(blob);
        const audio = document.createElement('audio');
        const removeBtn = document.createElement('button');
        removeBtn.innerText = 'Re-record';
        removeBtn.addEventListener('click', () => {
            audio.remove();
            removeBtn.remove();
            showRecordBtn();
        });
        audio.setAttribute('src', blobUrl);
        audio.setAttribute('controls', 'nodownload');
        list.appendChild(audio);
        list.appendChild(removeBtn);
      }

      function showRecordBtn() {
          var recordBtn = document.getElementById('recordBtn');
          recordBtn.style.display = "inline-block";
          recordBtn.click();
      }
}());