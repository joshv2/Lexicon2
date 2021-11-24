<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Alphabet $alphabet
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Alphabet'), ['action' => 'edit', $alphabet->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Alphabet'), ['action' => 'delete', $alphabet->id], ['confirm' => __('Are you sure you want to delete # {0}?', $alphabet->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Alphabets'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Alphabet'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="alphabets view content">
            <h3><?= h($alphabet->language_id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Language') ?></th>
                    <td><?= $alphabet->has('language') ? $this->Html->link($alphabet->language->name, ['controller' => 'Languages', 'action' => 'view', $alphabet->language->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('UTF8value') ?></th>
                    <td><?= h($alphabet->UTF8value) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($alphabet->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
