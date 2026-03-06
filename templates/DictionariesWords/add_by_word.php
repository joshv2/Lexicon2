<?php

/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $link
 * @var int $wordId
 * @var array $dictionaries
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(
                __('Back to dictionaries for this word'),
                ['action' => 'indexByWord', $wordId],
                ['class' => 'side-nav-item']
            ) ?>
        </div>
    </aside>

    <div class="column-responsive column-80">
        <div class="dictionariesWords form content">
            <?= $this->Form->create($link) ?>
            <fieldset>
                <legend><?= __('Add Dictionary Link') ?></legend>

                <?= $this->Form->control('word_id', ['type' => 'hidden', 'value' => $wordId]) ?>
                <p><?= h("Adding dictionary link for word ID: {$wordId}") ?></p>

                <?= $this->Form->control('dictionary_id', [
                    'label' => 'Dictionary',
                    'options' => $dictionaries,
                    'empty' => '(choose one)',
                ]) ?>
            </fieldset>

            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
