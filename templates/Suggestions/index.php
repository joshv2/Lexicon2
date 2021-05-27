<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Suggestion[]|\Cake\Collection\CollectionInterface $suggestions
 */
?>
<div class="suggestions index content">
    <?= $this->Html->link(__('New Suggestion'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Suggestions') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('word_id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('full_name') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('status') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suggestions as $suggestion): ?>
                <tr>
                    <td><?= $this->Number->format($suggestion->id) ?></td>
                    <td><?= $suggestion->has('word') ? $this->Html->link($suggestion->word->id, ['controller' => 'Words', 'action' => 'view', $suggestion->word->id]) : '' ?></td>
                    <td><?= $suggestion->has('user') ? $this->Html->link($suggestion->user->id, ['controller' => 'Users', 'action' => 'view', $suggestion->user->id]) : '' ?></td>
                    <td><?= h($suggestion->full_name) ?></td>
                    <td><?= h($suggestion->email) ?></td>
                    <td><?= h($suggestion->created) ?></td>
                    <td><?= h($suggestion->status) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $suggestion->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $suggestion->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $suggestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $suggestion->id)]) ?>
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
