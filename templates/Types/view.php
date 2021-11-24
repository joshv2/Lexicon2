<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Type $type
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Type'), ['action' => 'edit', $type->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Type'), ['action' => 'delete', $type->id], ['confirm' => __('Are you sure you want to delete # {0}?', $type->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Types'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Type'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="types view content">
            <h3><?= h($type->type) ?></h3>
            <table>
                <tr>
                    <th><?= __('Type') ?></th>
                    <td><?= h($type->type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Language') ?></th>
                    <td><?= $type->has('language') ? $this->Html->link($type->language->name, ['controller' => 'Languages', 'action' => 'view', $type->language->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($type->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Top') ?></th>
                    <td><?= $type->top ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Words') ?></h4>
                <?php if (!empty($type->words)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Spelling') ?></th>
                            <th><?= __('Etymology') ?></th>
                            <th><?= __('Etymology Json') ?></th>
                            <th><?= __('Notes') ?></th>
                            <th><?= __('Notes Json') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Approved') ?></th>
                            <th><?= __('Approved Date') ?></th>
                            <th><?= __('Language Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Full Name') ?></th>
                            <th><?= __('Email') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($type->words as $words) : ?>
                        <tr>
                            <td><?= h($words->id) ?></td>
                            <td><?= h($words->spelling) ?></td>
                            <td><?= h($words->etymology) ?></td>
                            <td><?= h($words->etymology_json) ?></td>
                            <td><?= h($words->notes) ?></td>
                            <td><?= h($words->notes_json) ?></td>
                            <td><?= h($words->created) ?></td>
                            <td><?= h($words->approved) ?></td>
                            <td><?= h($words->approved_date) ?></td>
                            <td><?= h($words->language_id) ?></td>
                            <td><?= h($words->user_id) ?></td>
                            <td><?= h($words->full_name) ?></td>
                            <td><?= h($words->email) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Words', 'action' => 'view', $words->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Words', 'action' => 'edit', $words->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Words', 'action' => 'delete', $words->id], ['confirm' => __('Are you sure you want to delete # {0}?', $words->id)]) ?>
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
