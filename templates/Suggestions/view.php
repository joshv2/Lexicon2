<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Suggestion $suggestion
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Suggestion'), ['action' => 'edit', $suggestion->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Suggestion'), ['action' => 'delete', $suggestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $suggestion->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Suggestions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Suggestion'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="suggestions view content">
            <h3><?= h($suggestion->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Word') ?></th>
                    <td><?= $suggestion->has('word') ? $this->Html->link($suggestion->word->id, ['controller' => 'Words', 'action' => 'view', $suggestion->word->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $suggestion->has('user') ? $this->Html->link($suggestion->user->id, ['controller' => 'Users', 'action' => 'view', $suggestion->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Full Name') ?></th>
                    <td><?= h($suggestion->full_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($suggestion->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= h($suggestion->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($suggestion->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($suggestion->created) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Suggestion') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($suggestion->suggestion)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
