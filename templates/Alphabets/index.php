<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Alphabet[]|\Cake\Collection\CollectionInterface $alphabets
 */
?>
<div class="alphabets index content">
    <?= $this->Html->link(__('New Alphabet'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Alphabets') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('language_id') ?></th>
                    <th><?= $this->Paginator->sort('UTF8value') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alphabets as $alphabet): ?>
                <tr>
                    <td><?= $this->Number->format($alphabet->id) ?></td>
                    <td><?= $alphabet->has('language') ? $this->Html->link($alphabet->language->name, ['controller' => 'Languages', 'action' => 'view', $alphabet->language->id]) : '' ?></td>
                    <td><?= h($alphabet->UTF8value) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $alphabet->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $alphabet->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $alphabet->id], ['confirm' => __('Are you sure you want to delete # {0}?', $alphabet->id)]) ?>
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
