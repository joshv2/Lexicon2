<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Word[]|\Cake\Collection\CollectionInterface $words
 */
?>
<div class="words index content">
    <?= $this->Html->link(__('New Word'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Words') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('spelling') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('approved') ?></th>
                    <th><?= $this->Paginator->sort('language_id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($words as $word): ?>
                <tr>
                    <td><?= $this->Number->format($word->id) ?></td>
                    <td><?= h($word->spelling) ?></td>
                    <td><?= h($word->created) ?></td>
                    <td><?= h($word->approved) ?></td>
                    <td><?= $this->Number->format($word->language_id) ?></td>
                    <td><?= h($word->user_id) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $word->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $word->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $word->id], ['confirm' => __('Are you sure you want to delete # {0}?', $word->id)]) ?>
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
