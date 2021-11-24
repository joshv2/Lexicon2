<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Origin[]|\Cake\Collection\CollectionInterface $origins
 */
?>
<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
</nav>
<div class="origins index content">
    <?= $this->Html->link(__('New Origin'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Origins') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('origin') ?></th>
                    <th><?= $this->Paginator->sort('top') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($origins as $origin): ?>
                <tr>
                    <td><?= $this->Number->format($origin->id) ?></td>
                    <td><?= h($origin->origin) ?></td>
                    <td><?= h($origin->top) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $origin->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $origin->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $origin->id], ['confirm' => __('Are you sure you want to delete # {0}?', $origin->id)]) ?>
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
