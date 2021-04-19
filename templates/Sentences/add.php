<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Sentence $sentence
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Sentences'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="sentences form content">
            <?= $this->Form->create($sentence) ?>
            <fieldset>
                <legend><?= __('Add Sentence') ?></legend>
                <?php
                    echo $this->Form->control('word_id', ['options' => $words]);
                    echo $this->Form->control('sentence');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
