<?php

/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface|\Cake\Collection\CollectionInterface $dictionaryLinks
 * @var int $wordId
 */
?>
<div class="dictionariesWords index content">
    <h3><?= h("Dictionaries for Word #{$wordId}") ?></h3>

    <p>
        <?= $this->Html->link('Add dictionary link', ['action' => 'addByWord', $wordId]) ?>
    </p>

    <?php $dictionaryLinkItems = method_exists($dictionaryLinks, 'items') ? $dictionaryLinks->items() : $dictionaryLinks; ?>

    <?php if ($dictionaryLinkItems->isEmpty()): ?>
        <p>No dictionaries linked to this word.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('DictionariesWords.id', 'Link ID') ?></th>
                    <th>Dictionary</th>
                    <th><?= $this->Paginator->sort('DictionariesWords.dictionary_id', 'Dictionary ID') ?></th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dictionaryLinkItems as $link): ?>
                    <tr>
                        <td><?= h($link->id) ?></td>
                        <td><?= h($link->dictionary->dictionary ?? '') ?></td>
                        <td><?= h($link->dictionary_id) ?></td>
                        <td class="actions">
                            <?= $this->Form->postLink(
                                'Delete link',
                                ['action' => 'delete', $link->id],
                                ['confirm' => 'Delete this dictionary link?']
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
