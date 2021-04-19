<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pronunciation $pronunciation
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Pronunciation'), ['action' => 'edit', $pronunciation->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Pronunciation'), ['action' => 'delete', $pronunciation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pronunciation->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Pronunciations'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Pronunciation'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="pronunciations view content">
            <h3><?= h($pronunciation->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Word') ?></th>
                    <td><?= $pronunciation->has('word') ? $this->Html->link($pronunciation->word->id, ['controller' => 'Words', 'action' => 'view', $pronunciation->word->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Spelling') ?></th>
                    <td><?= h($pronunciation->spelling) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sound File') ?></th>
                    <td><?= h($pronunciation->sound_file) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pronunciation') ?></th>
                    <td><?= h($pronunciation->pronunciation) ?></td>
                </tr>
                <tr>
                    <th><?= __('Notes') ?></th>
                    <td><?= h($pronunciation->notes) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($pronunciation->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
