<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\RegionsWord $link
 * @var int $wordId
 * @var array $regions
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(
                __('Back to regions for this word'),
                ['action' => 'indexByWord', $wordId],
                ['class' => 'side-nav-item']
            ) ?>
        </div>
    </aside>

    <div class="column-responsive column-80">
        <div class="regionsWords form content">
            <?= $this->Form->create($link) ?>
            <fieldset>
                <legend><?= __('Add Region Link') ?></legend>

                <?= $this->Form->control('word_id', ['type' => 'hidden', 'value' => $wordId]) ?>
                <p><?= h("Adding region link for word ID: {$wordId}") ?></p>

                <?= $this->Form->control('region_id', [
                    'label' => 'Region',
                    'options' => $regions,
                    'empty' => '(choose one)',
                ]) ?>
            </fieldset>

            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>