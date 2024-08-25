<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Origin $origin
 */
?>

<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Origins'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="origins form content">
            <?= $this->Form->create($origin) ?>
            <fieldset>
                <legend><?= __('Add Origin') ?></legend>
                <?php
                    echo $this->Form->control('origin');
                    echo $this->Form->control('top');
                    //echo $this->Form->control('words._ids', ['options' => $words]);
                    echo $this->Form->hidden('language_id', ['value' => $sitelang->id]);
                    //echo $this->Form->control('words._ids', ['options' => $words]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
