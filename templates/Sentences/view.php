<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Sentence $sentence
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Sentence'), ['action' => 'edit', $sentence->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Sentence'), ['action' => 'delete', $sentence->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sentence->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Sentences'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Sentence'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="sentences view content">
            <h3><?= h($sentence->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Word') ?></th>
                    <td><?= $sentence->has('word') ? $this->Html->link($sentence->word->id, ['controller' => 'Words', 'action' => 'view', $sentence->word->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($sentence->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Sentence') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($sentence->sentence)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Sentence Recordings') ?></h4>
                <?php if (!empty($sentence->sentence_recordings)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Sentence Id') ?></th>
                            <th><?= __('Sound File') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($sentence->sentence_recordings as $sentenceRecordings) : ?>
                        <tr>
                            <td><?= h($sentenceRecordings->id) ?></td>
                            <td><?= h($sentenceRecordings->sentence_id) ?></td>
                            <td><?= h($sentenceRecordings->sound_file) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'SentenceRecordings', 'action' => 'view', $sentenceRecordings->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'SentenceRecordings', 'action' => 'edit', $sentenceRecordings->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'SentenceRecordings', 'action' => 'delete', $sentenceRecordings->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sentenceRecordings->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
