<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Alternate $alternate
 * @var \App\Model\Entity\Word $word
 * @var string|int $wordId
 */
?>

<div class="column-responsive column-80">
    <h2>
        <?= __('Edit alternate spelling for {0}', $this->Html->link(h($word->spelling), ['prefix' => false, 'controller' => 'Words', 'action' => 'view', $word->id], ['escape' => false])) ?>
    </h2>

    <div class="alternates form content">
        <?= $this->Form->create($alternate, ['url' => ['prefix' => false, 'controller' => 'Alternates', 'action' => 'edit', $alternate->id, $wordId]]) ?>
        <?= $this->Form->control('spelling', ['label' => __('Spelling')]) ?>

        <div style="margin-top: 10px;">
            <?= $this->Form->button(__('Save'), ['class' => 'button blue']) ?>
            <?= $this->Html->link(__('Cancel'), ['prefix' => false, 'controller' => 'Alternates', 'action' => 'manage', $wordId], ['class' => 'button']) ?>
            <?= $this->Form->postLink(
                __('Delete'),
                ['prefix' => false, 'controller' => 'Alternates', 'action' => 'delete', $alternate->id, $wordId],
                ['confirm' => __('Are you sure you want to delete "{0}"?', $alternate->spelling), 'class' => 'button red']
            ) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
