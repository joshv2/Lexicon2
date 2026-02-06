<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Sentence[]|\Cake\Collection\CollectionInterface $sentences
 * @var int|null $wordId
 */
?>
<div class="sentences index content">
    <?= $this->Html->link(
        __('New Sentence'),
        $wordId !== null ? ['action' => 'add', $wordId] : ['action' => 'add'],
        ['class' => 'button float-right']
    ) ?>

    <h3>
        <?= $wordId !== null ? __('Sentences for Word #{0}', $wordId) : __('Sentences') ?>
    </h3>

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
                <?php foreach ($sentences as $sentence): ?>
                <tr>
                    <td><?= $this->Number->format($sentence->id) ?></td>
                    <td><?= $sentence->has('word') ? $this->Html->link($sentence->word->id, ['controller' => 'Words', 'action' => 'view', $sentence->word->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $sentence->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sentence->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sentence->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sentence->id)]) ?>
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
