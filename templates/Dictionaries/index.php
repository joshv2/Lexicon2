<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dictionary[]|\Cake\Collection\CollectionInterface $dictionaries
 */
?>
<div class="dictionaries index content">
    <?= $this->Html->link(__('New Dictionary'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Dictionaries') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('dictionary') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dictionaries as $dictionary): ?>
                <tr>
                    <td><?= $this->Number->format($dictionary->id) ?></td>
                    <td><?= h($dictionary->dictionary) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $dictionary->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $dictionary->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $dictionary->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dictionary->id)]) ?>
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
