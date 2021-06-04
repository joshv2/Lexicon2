<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pronunciation $pronunciation
 */
?>
<div class="row">
   
    <div class="column-responsive column-80">
        <div class="pronunciations form content">
            <?= $this->Form->create($pronunciation) ?>
            <fieldset>
                <legend><?= __('Deny Pronunciation') ?></legend>
                <?php

                    echo $this->Form->control('notes', ['label' => 'Message for Recorder']);
                    echo $this->Form->hidden('approved', ['value' => -1]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
