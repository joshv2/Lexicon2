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
            <?= $this->Html->link(__('List Sentence Recordings'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="sentenceRecordings form content">
            <?= $this->Form->create($sentenceRecording) ?>
            <fieldset>
                <legend><?= __('Add Sentence Recording') ?></legend>
                <ul>
                <?php foreach ($sentences as $s): ?>
                    <li><?php echo $s->sentence; ?></li>
                <?php endforeach ?>
                </ul>
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
