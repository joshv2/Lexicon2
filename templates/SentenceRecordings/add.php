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
                <legend><?= __('Add Sentence Recording for') ?></legend>
                
                    <?php echo "<div class='readingSentence'>" . strip_tags($sentences[0]->sentence) . 
                    $this->Form->button(__('Record'), ['class' => 'btn-record button', 'id' => 'record']) .
                    $this->Form->control(__('soundfile0'), [
                        'class' => 'recording-input',
                        'type' => 'file',
                        'style' => 'display:none',
                        'label' => FALSE
                    ]) .
                    "</div>"; ?>
                    <div class='readingSentence2'>
                    <br/><br/>
                    <p>When you are finished recording, please press submit.</p>
                    </div>


            </fieldset>
            <div class='readingSentence2'>
            <?= $this->Form->button(__('Submit'), ['class' => 'button blue2']) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
