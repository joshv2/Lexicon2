<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Region $region
 */
?>
<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
</nav>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Region'), ['action' => 'edit', $region->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Region'), ['action' => 'delete', $region->id], ['confirm' => __('Are you sure you want to delete # {0}?', $region->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Regions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Region'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="regions view content">
            <h3><?= h($region->region) ?></h3>
            <table>
                <tr>
                    <th><?= __('Region') ?></th>
                    <td><?= h($region->region) ?></td>
                </tr>
                <tr>
                    <th><?= __('Language') ?></th>
                    <td><?= $region->has('language') ? $this->Html->link($region->language->name, ['controller' => 'Languages', 'action' => 'view', $region->language->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($region->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Top') ?></th>
                    <td><?= $region->top ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Words') ?></h4>
                <?php if (!empty($region->words)) : ?>
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
                        <?php foreach ($region->words as $words) : ?>
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
