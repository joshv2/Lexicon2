<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SentenceRecording $sentenceRecording
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Sentence Recording'), ['action' => 'edit', $sentenceRecording->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Sentence Recording'), ['action' => 'delete', $sentenceRecording->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sentenceRecording->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Sentence Recordings'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Sentence Recording'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="sentenceRecordings view content">
            <h3><?= h($sentenceRecording->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Sentence') ?></th>
                    <td><?= $sentenceRecording->has('sentence') ? $this->Html->link($sentenceRecording->sentence->id, ['controller' => 'Sentences', 'action' => 'view', $sentenceRecording->sentence->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Sound File') ?></th>
                    <td><?= h($sentenceRecording->sound_file) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($sentenceRecording->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
