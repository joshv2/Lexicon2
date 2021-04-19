<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pronunciation $pronunciation
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $pronunciation->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $pronunciation->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Pronunciations'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="pronunciations form content">
            <?= $this->Form->create($pronunciation) ?>
            <fieldset>
                <legend><?= __('Edit Pronunciation') ?></legend>
                <?php
                    echo $this->Form->control('word_id', ['options' => $words]);
                    echo $this->Form->control('spelling');
                    echo $this->Form->control('sound_file');
                    echo $this->Form->control('pronunciation');
                    echo $this->Form->control('notes');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
