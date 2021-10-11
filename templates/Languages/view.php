<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Language $language
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Language'), ['action' => 'edit', $language->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Language'), ['action' => 'delete', $language->id], ['confirm' => __('Are you sure you want to delete # {0}?', $language->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Languages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Language'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="languages view content">
            <h3><?= h($language->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($language->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subdomain') ?></th>
                    <td><?= h($language->subdomain) ?></td>
                </tr>
                <tr>
                    <th><?= __('HeaderImage') ?></th>
                    <td><?= h($language->HeaderImage) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($language->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('AboutSec1Header') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($language->AboutSec1Header)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('AboutSec1Text') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($language->AboutSec1Text)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('AboutSec2Header') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($language->AboutSec2Header)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('AboutSec2Text') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($language->AboutSec2Text)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('AboutSec3Header') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($language->AboutSec3Header)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('AboutSec3Text') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($language->AboutSec3Text)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('AboutSec4Header') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($language->AboutSec4Header)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('AboutSec4Text') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($language->AboutSec4Text)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('NotesSec1Header') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($language->NotesSec1Header)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('NotesSec1Text') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($language->NotesSec1Text)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Words') ?></h4>
                <?php if (!empty($language->words)) : ?>
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
                        <?php foreach ($language->words as $words) : ?>
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
