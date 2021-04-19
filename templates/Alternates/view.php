<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Alternate $alternate
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Alternate'), ['action' => 'edit', $alternate->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Alternate'), ['action' => 'delete', $alternate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $alternate->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Alternates'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Alternate'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="alternates view content">
            <h3><?= h($alternate->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Word') ?></th>
                    <td><?= $alternate->has('word') ? $this->Html->link($alternate->word->id, ['controller' => 'Words', 'action' => 'view', $alternate->word->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Spelling') ?></th>
                    <td><?= h($alternate->spelling) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($alternate->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
