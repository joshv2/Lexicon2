<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Word $word
 * @var \Cake\Datasource\ResultSetInterface|\App\Model\Entity\Alternate[] $alternates
 */
?>

<div class="column-responsive column-80">
    <h2>
        <?= __('Manage alternate spellings for {0}', $this->Html->link(h($word->spelling), ['prefix' => false, 'controller' => 'Words', 'action' => 'view', $word->id], ['escape' => false])) ?>
    </h2>

    <div class="alternates form content">
        <p><?= __('Add multiple alternate spellings at once (one per line; commas are also accepted).') ?></p>

        <?= $this->Form->create(null, ['url' => ['prefix' => false, 'controller' => 'Alternates', 'action' => 'manage', $word->id]]) ?>
        <?= $this->Form->textarea('alternate_spellings', [
            'label' => false,
            'rows' => 6,
            'style' => 'width: 100%; max-width: 700px;',
            'placeholder' => "e.g.\ncolour\ncolur",
        ]) ?>
        <div style="margin-top: 10px;">
            <?= $this->Form->button(__('Add Alternates'), ['type' => 'submit', 'class' => 'button blue']) ?>
            <?= $this->Html->link(__('Back to Word'), ['prefix' => false, 'controller' => 'Words', 'action' => 'view', $word->id], ['class' => 'button']) ?>
        </div>
        <?= $this->Form->end() ?>

        <hr />

        <h3><?= __('Existing alternate spellings') ?></h3>

        <?php if (empty($alternates) || $alternates->count() === 0): ?>
            <p><?= __('No alternate spellings yet.') ?></p>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><?= __('Spelling') ?></th>
                            <th style="width: 200px;"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alternates as $alt): ?>
                            <tr>
                                <td><?= h($alt->spelling) ?></td>
                                <td style="white-space: nowrap;">
                                    <?= $this->Html->link(
                                        __('Edit'),
                                        ['prefix' => false, 'controller' => 'Alternates', 'action' => 'edit', $alt->id, $word->id],
                                        ['class' => 'button blue']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        __('Delete'),
                                        ['prefix' => false, 'controller' => 'Alternates', 'action' => 'delete', $alt->id, $word->id],
                                        ['confirm' => __('Are you sure you want to delete "{0}"?', $alt->spelling), 'class' => 'button red']
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
