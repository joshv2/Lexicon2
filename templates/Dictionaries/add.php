<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dictionary $dictionary
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Dictionaries'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="dictionaries form content">
            <?= $this->Form->create($dictionary) ?>
            <fieldset>
                <legend><?= __('Add Dictionary') ?></legend>
                <?php
                    echo $this->Form->control('dictionary');
                    echo $this->Form->control('words._ids', ['options' => $words]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
