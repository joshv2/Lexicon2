<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SentenceRecording[]|\Cake\Collection\CollectionInterface $sentenceRecordings
 */
?>
<div class="sentenceRecordings index content">
    <?= $this->Html->link(__('New Sentence Recording'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Sentence Recordings') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('sentence_id') ?></th>
                    <th><?= $this->Paginator->sort('sound_file') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sentenceRecordings as $sentenceRecording): ?>
                <tr>
                    <td><?= $this->Number->format($sentenceRecording->id) ?></td>
                    <td><?= $sentenceRecording->has('sentence') ? $this->Html->link($sentenceRecording->sentence->id, ['controller' => 'Sentences', 'action' => 'view', $sentenceRecording->sentence->id]) : '' ?></td>
                    <td><?= h($sentenceRecording->sound_file) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $sentenceRecording->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sentenceRecording->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sentenceRecording->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sentenceRecording->id)]) ?>
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
