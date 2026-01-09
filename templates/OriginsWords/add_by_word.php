<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\OriginsWord $link
 * @var int $wordId
 * @var array $origins
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(
                __('Back to origins for this word'),
                ['action' => 'indexByWord', $wordId],
                ['class' => 'side-nav-item']
            ) ?>
        </div>
    </aside>

    <div class="column-responsive column-80">
        <div class="originsWords form content">
            <?= $this->Form->create($link) ?>
            <fieldset>
                <legend><?= __('Add Origin Link') ?></legend>

                <?= $this->Form->control('word_id', ['type' => 'hidden', 'value' => $wordId]) ?>
                <p><?= h("Adding origin link for word ID: {$wordId}") ?></p>

                <?= $this->Form->control('origin_id', [
                    'label' => 'Origin',
                    'options' => $origins,
                    'empty' => '(choose one)'
                ]) ?>
            </fieldset>

            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>