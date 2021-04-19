<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Alternate $alternate
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $alternate->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $alternate->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Alternates'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="alternates form content">
            <?= $this->Form->create($alternate) ?>
            <fieldset>
                <legend><?= __('Edit Alternate') ?></legend>
                <?php
                    echo $this->Form->control('word_id', ['options' => $words]);
                    echo $this->Form->control('spelling');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
