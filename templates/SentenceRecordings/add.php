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
                        <span class="record-success" style="display: none;">Recorded <i class="icon-ok"></i></span>    
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
                        <input type="file" name="soundfile1" id="soundfile1" style="width: 176px;" accept=".mp3">
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
<?= $this->Html->script('uploadtoggle')."\n";?>
<?= $this->Html->script('detectios')."\n";?>