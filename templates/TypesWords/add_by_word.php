<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TypesWord $link
 * @var int $wordId
 * @var array $types
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(
                __('Back to types for this word'),
                ['action' => 'indexByWord', $wordId],
                ['class' => 'side-nav-item']
            ) ?>
        </div>
    </aside>

    <div class="column-responsive column-80">
        <div class="typesWords form content">
            <?= $this->Form->create($link) ?>
            <fieldset>
                <legend><?= __('Add Type Link') ?></legend>

                <?= $this->Form->control('word_id', ['type' => 'hidden', 'value' => $wordId]) ?>
                <p><?= h("Adding type link for word ID: {$wordId}") ?></p>

                <?= $this->Form->control('type_id', [
                    'label' => 'Type',
                    'options' => $types,
                    'empty' => '(choose one)',
                ]) ?>
            </fieldset>

            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>