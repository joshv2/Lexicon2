<?php

/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface|\Cake\Collection\CollectionInterface $regionLinks
 * @var int $wordId
 */
?>
<div class="regionsWords index content">
    <h3><?= h("Regions for Word #{$wordId}") ?></h3>

    <p>
        <?= $this->Html->link('Add region link', ['action' => 'addByWord', $wordId]) ?>
    </p>

    <?php $regionLinkItems = method_exists($regionLinks, 'items') ? $regionLinks->items() : $regionLinks; ?>

    <?php if ($regionLinkItems->isEmpty()): ?>
        <p>No regions linked to this word.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('RegionsWords.id', 'Link ID') ?></th>
                    <th>Region</th>
                    <th><?= $this->Paginator->sort('RegionsWords.region_id', 'Region ID') ?></th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regionLinkItems as $link): ?>
                    <tr>
                        <td><?= h($link->id) ?></td>
                        <td><?= h($link->region->region ?? '') ?></td>
                        <td><?= h($link->region_id) ?></td>
                        <td class="actions">
                            <?= $this->Form->postLink(
                                'Delete link',
                                ['action' => 'delete', $link->id],
                                ['confirm' => 'Delete this region link?']
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