<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pronunciation[]|\Cake\Collection\CollectionInterface $pronunciations
 */
?>
<div class="pronunciations index content">
    <?= $this->Html->link(__('New Pronunciation'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Pronunciations') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('word_id') ?></th>
                    <th><?= $this->Paginator->sort('spelling') ?></th>
                    <th><?= $this->Paginator->sort('sound_file') ?></th>
                    <th><?= $this->Paginator->sort('pronunciation') ?></th>
                    <th><?= $this->Paginator->sort('notes') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pronunciations as $pronunciation): ?>
                <tr>
                    <td><?= $this->Number->format($pronunciation->id) ?></td>
                    <td><?= $pronunciation->has('word') ? $this->Html->link(__($pronunciation->word->id), ['controller' => 'Words', 'action' => 'view', $pronunciation->word->id]) : '' ?></td>
                    <td><?= h($pronunciation->spelling) ?></td>
                    <td><?= h($pronunciation->sound_file) ?></td>
                    <td><?= h($pronunciation->pronunciation) ?></td>
                    <td><?= h($pronunciation->notes) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $pronunciation->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $pronunciation->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $pronunciation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pronunciation->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
