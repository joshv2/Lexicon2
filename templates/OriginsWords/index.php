<?php

/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface|\Cake\Collection\CollectionInterface $originLinks
 * @var int $wordId
 */
?>
<div class="originsWords index content">
    <h3><?= h("Origins for Word #{$wordId}") ?></h3>

    <p>
        <?= $this->Html->link(
            'Add origin link',
            ['action' => 'addByWord', $wordId]
        ) ?>
    </p>

    <?php $originLinkItems = method_exists($originLinks, 'items') ? $originLinks->items() : $originLinks; ?>

    <?php if ($originLinkItems->isEmpty()): ?>
        <p>No origins linked to this word.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('OriginsWords.id', 'Link ID') ?></th>
                    <th><?= $this->Paginator->sort('Origins.origin', 'Origin') ?></th>
                    <th><?= $this->Paginator->sort('OriginsWords.origin_id', 'Origin ID') ?></th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($originLinkItems as $link): ?>
                    <tr>
                        <td><?= h($link->id) ?></td>
                        <td><?= h($link->origin->origin ?? '') ?></td>
                        <td><?= h($link->origin_id) ?></td>
                        <td class="actions">
                            <?= $this->Form->postLink(
                                'Delete link',
                                ['action' => 'delete', $link->id],
                                [
                                    'confirm' => 'Delete this origin link?',
                                    'class' => 'side-nav-item'
                                ]
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