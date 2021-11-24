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
            <?= $this->Html->link(__('Return to Moderators Panel'), ['controller' => 'moderators', 'action' => 'index'], ['class' => 'side-nav-item']) ?>
            
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
                    echo $this->Form->control('hasOrigins');
                    echo $this->Html->link(__('Add Origins'), ['controller' => 'origins', 'action' => 'add'], ['target' => '_blank']);
                    echo $this->Form->control('hasRegions');
                    echo $this->Html->link(__('Add Regions'), ['controller' => 'regions', 'action' => 'add'], ['target' => '_blank']);
                    echo $this->Form->control('hasTypes');
                    echo $this->Html->link(__('Add Types'), ['controller' => 'types', 'action' => 'add'], ['target' => '_blank']);
                    echo $this->Form->control('hasDictionaries');
                    echo $this->Html->link(__('Add Dictionaries'), ['controller' => 'dictionaries', 'action' => 'add'], ['target' => '_blank']);
                    echo "<div class='utf8'>";
                    echo $this->Form->control('UTFRangeStart');
                    echo "<div><a href='https://www.fileformat.info/info/charset/UTF-8'>List of UTF 8 Characters</a></div></div>";
                    echo $this->Form->control('UTFRangeEnd');
                    echo $this->Html->link(__('Add additional characters out of range'), ['controller' => 'alphabets', 'action' => 'add']);
                    echo $this->Form->control('righttoleft');

                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
