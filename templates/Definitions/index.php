<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Definition[]|\Cake\Collection\CollectionInterface $definitions
 */
?>
<div class="definitions index content">
    <?= $this->Html->link(__('New Definition'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Definitions') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('word_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($definitions as $definition): ?>
                <tr>
                    <td><?= $this->Number->format($definition->id) ?></td>
                    <td><?= $definition->has('word') ? $this->Html->link($definition->word->id, ['controller' => 'Words', 'action' => 'view', $definition->word->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $definition->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $definition->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $definition->id], ['confirm' => __('Are you sure you want to delete # {0}?', $definition->id)]) ?>
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
