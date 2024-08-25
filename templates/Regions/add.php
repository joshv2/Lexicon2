<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Region $region
 */
?>

<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Regions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="regions form content">
            <?= $this->Form->create($region) ?>
            <fieldset>
                <legend><?= __('Add Region') ?></legend>
                <?php
                    echo $this->Form->control('region');
                    echo $this->Form->hidden('language_id', ['value' => $sitelang->id]);
                    echo $this->Form->control('top');
                    //echo $this->Form->control('words._ids', ['options' => $words]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
