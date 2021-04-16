<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Word $word
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Words'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="words form content">
            <?= $this->Form->create($word) ?>
            <fieldset>
                <legend><?= __('Add Word') ?></legend>
                <?php
                    echo $this->Form->control('spelling');
                    echo $this->Form->control('etymology');
                    echo $this->Form->control('notes');
                    echo $this->Form->control('approved');
                    echo $this->Form->control('language_id');
                    echo $this->Form->control('user_id');
                    echo $this->Form->control('dictionaries._ids', ['options' => $dictionaries]);
                    echo $this->Form->control('origins._ids', ['options' => $origins]);
                    echo $this->Form->control('regions._ids', ['options' => $regions]);
                    echo $this->Form->control('types._ids', ['options' => $types]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
