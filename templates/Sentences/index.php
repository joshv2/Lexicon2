<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Sentence[]|\Cake\Collection\CollectionInterface $sentences
 * @var int|null $wordId
 * @var \App\Model\Entity\Word|null $word
 */

use Cake\Utility\Text;

$word = $word ?? null;
?>
<div class="sentences index content">
    <?= $this->Html->link(
        __('Add a Sentence'),
        $wordId !== null ? ['action' => 'add', $wordId] : ['action' => 'add'],
        ['class' => 'button float-right']
    ) ?>

    <h3>
        <?php if ($wordId !== null && $word !== null): ?>
            <?= __('Sentences for {0}', $this->Html->link($word->spelling, ['controller' => 'Words', 'action' => 'view', $word->id])) ?>
        <?php elseif ($wordId !== null): ?>
            <?= __('Sentences for Word #{0}', $wordId) ?>
        <?php else: ?>
            <?= __('Sentences') ?>
        <?php endif; ?>
    </h3>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <?php if ($wordId === null): ?>
                        <th><?= $this->Paginator->sort('word_id') ?></th>
                    <?php endif; ?>
                    <th><?= __('Sentence') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sentences as $sentence): ?>
                <tr>
                    <td><?= $this->Number->format($sentence->id) ?></td>
                    <?php if ($wordId === null): ?>
                        <td>
                            <?php if ($sentence->has('word')): ?>
                                <?= $this->Html->link($sentence->word->spelling ?? (string)$sentence->word->id, ['controller' => 'Words', 'action' => 'view', $sentence->word->id]) ?>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <td>
                        <?php
                            $plain = trim(preg_replace('/\s+/', ' ', strip_tags((string)($sentence->sentence ?? ''))));
                            $plain = html_entity_decode($plain, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            echo h(Text::truncate($plain, 220, ['ellipsis' => '…']));
                        ?>
                    </td>
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
