<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SentenceRecording $sentenceRecording
 */
?>


<div class="row">
    <div class="column-responsive column-80">
        <div class="sentenceRecordings form content">
            <?= $this->Form->create($sentenceRecording, ['id' => 'add_form','enctype' => 'multipart/form-data']) ?>
            <fieldset>
                <legend><?= __('Add a recording of this sentence:') ?></legend>
                
                    <?php echo "<div class='readingSentence'>" . strip_tags($sentences[0]->sentence) . 
                    '<br/><br/>'
                    
                    .
                    #$this->Form->button(__('Record'), ['class' => 'btn-record button', 'id' => 'record']) .
                    
                    '</div>
                    <div class="readingSentence">
                    <input type="radio" name="option" id="showButton" checked>
                    <label for="showButton">Record on Your Device</label>
                    <input type="radio" name="option" id="showUploadBox">
                    <label for="showUploadBox">Upload a Recording</label>
                    <div id="buttonContainer">
                        <button class="btn-record button" id="record" type="submit">Click to Record</button>
                        ' . $this->Form->control(__('soundfile0'), [
                            'class' => 'recording-input',
                            'type' => 'file',
                            'style' => 'display:none',
                            'label' => FALSE
                        ]) . 
                        '
                    </div>

                    <div id="uploadBoxContainer" style="display:none">
                        <input type="file" name="soundfile0" id="soundfile0" style="width: 176px;" accept=".mp3">
                    </div></div>'; ?>
                    <div class='readingSentence2'>
                    <br/><br/>
                    <p><?= __('When you are finished recording, please press submit.')?> <a href="/notes/#bottom"><?= __('View tips on making a high-quality recording.')?></a></p>
                    </div>


            </fieldset>
            <div class='readingSentence2'>
            <?= $this->Form->button(__('Submit'), ['class' => 'button blue2']) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>


<script>
    const toggleButton = document.getElementById("record");
    const fileInput = document.getElementById("fileInput");
    const buttonContainer = document.getElementById("buttonContainer");
    const uploadBoxContainer = document.getElementById("uploadBoxContainer");

    document.querySelectorAll('input[type=radio][name=option]').forEach((radio) => {
      radio.addEventListener('change', (e) => {
        if (e.target.id === "showButton") {
          buttonContainer.style.display = "block";
          uploadBoxContainer.style.display = "none";
        } else if (e.target.id === "showUploadBox") {
          buttonContainer.style.display = "none";
          uploadBoxContainer.style.display = "block";
        }
      });
    });
  </script>
<script type="text/javascript">
	if((iOS() == true)){
		$(".readingSentence").empty();
        $(".readingSentence").append("Recording is not available on iOS at this time.")
	}
	</script>   