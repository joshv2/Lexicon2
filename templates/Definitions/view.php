<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Definition $definition
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Definition'), ['action' => 'edit', $definition->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Definition'), ['action' => 'delete', $definition->id], ['confirm' => __('Are you sure you want to delete # {0}?', $definition->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Definitions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Definition'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="definitions view content">
            <h3><?= h($definition->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Word') ?></th>
                    <td><?= $definition->has('word') ? $this->Html->link($definition->word->id, ['controller' => 'Words', 'action' => 'view', $definition->word->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($definition->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Definition') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($definition->definition)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
