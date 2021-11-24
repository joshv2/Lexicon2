<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Alphabet $alphabet
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $alphabet->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $alphabet->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Alphabets'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="alphabets form content">
            <?= $this->Form->create($alphabet) ?>
            <fieldset>
                <legend><?= __('Edit Alphabet') ?></legend>
                <?php
                    echo $this->Form->control('language_id', ['options' => $languages]);
                    echo $this->Form->control('UTF8value');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
