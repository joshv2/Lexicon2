<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SentenceRecording $sentenceRecording
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sentenceRecording->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sentenceRecording->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Sentence Recordings'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="sentenceRecordings form content">
            <?= $this->Form->create($sentenceRecording) ?>
            <fieldset>
                <legend><?= __('Edit Sentence Recording') ?></legend>
                <?php
                    echo $this->Form->control('sentence_id', ['options' => $sentences]);
                    echo $this->Form->control('sound_file');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
