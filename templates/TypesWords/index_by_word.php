<?php

/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface|\Cake\Collection\CollectionInterface $typeLinks
 * @var int $wordId
 */
?>
<div class="typesWords index content">
    <h3><?= h("Types for Word #{$wordId}") ?></h3>

    <p>
        <?= $this->Html->link('Add type link', ['action' => 'addByWord', $wordId]) ?>
    </p>

    <?php if ($typeLinks->isEmpty()): ?>
        <p>No types linked to this word.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('TypesWords.id', 'Link ID') ?></th>
                    <th>Type</th>
                    <th><?= $this->Paginator->sort('TypesWords.type_id', 'Type ID') ?></th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($typeLinks as $link): ?>
                    <tr>
                        <td><?= h($link->id) ?></td>
                        <td><?= h($link->type->type ?? '') ?></td>
                        <td><?= h($link->type_id) ?></td>
                        <td class="actions">
                            <?= $this->Form->postLink(
                                'Delete link',
                                ['action' => 'delete', $link->id],
                                ['confirm' => 'Delete this type link?']
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< first') ?>
                <?= $this->Paginator->prev('< prev') ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next('next >') ?>
                <?= $this->Paginator->last('last >>') ?>
            </ul>
            <p><?= $this->Paginator->counter('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total') ?></p>
        </div>
    <?php endif; ?>
</div>