<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Language $language
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Languages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="languages form content">
            <?= $this->Form->create($language, array('type' => 'file')) ?>
            <fieldset>
                <legend><?= __('Add Language') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('subdomain');
                    echo $this->Form->control('translationfile', ['type' => 'file']);
                    echo $this->Form->control('HeaderImage', ['type' => 'file']);
                    echo $this->Form->control('LogoImage', ['type' => 'file']);
                    echo $this->Form->control('AboutSec1Header');
                    echo $this->Form->control('AboutSec1Text');
                    echo $this->Form->control('AboutSec2Header');
                    echo $this->Form->control('AboutSec2Text');
                    echo $this->Form->control('AboutSec3Header');
                    echo $this->Form->control('AboutSec3Text');
                    echo $this->Form->control('AboutSec4Header');
                    echo $this->Form->control('AboutSec4Text');
                    echo $this->Form->control('NotesSec1Header');
                    echo $this->Form->control('NotesSec1Text');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
