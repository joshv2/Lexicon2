<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Word $word
 * @var \Cake\Datasource\ResultSetInterface|array $requested_pronunciations
 */
?>
<?php
function status_to_words($approved)
{
    switch ($approved) {
        case 0:
            return 'Pending';
        case 1:
            return 'Approved';
        case -1:
            return 'Denied';
        default:
            return 'Unknown';
    }
}

function status_to_symbol($approved)
{
    switch ($approved) {
        case 0:
            return '…';
        case 1:
            return '✓';
        case -1:
            return '✗';
        default:
            return '?';
    }
}
?>

<style>
    .pron-manage-table.edit_table td,
    .pron-manage-table.edit_table th {
        padding: 8px;
    }

    .pron-manage-table.edit_table th {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pron-manage-table input[type="text"],
    .pron-manage-table input[type="number"],
    .pron-manage-table select {
        font-size: 16px;
        padding: 3px 3px;
        height: 32px;
        box-sizing: border-box;
    }

    .pron-manage-table select {
        line-height: 32px;
    }

    .pron-manage-table .status-col {
        text-align: center;
        white-space: nowrap;
    }

    .pron-manage-table .user-col {
        white-space: nowrap;
    }
</style>
<div class="column-responsive column-80">
<h2><?php echo 'Manage pronunciations for ' . $this->Html->link($word->spelling, ['controller' => 'Words', 'action' => 'view', $word->id]); ?></h2>
    <div class="pronunciations form content">
        <p><?=__("Approved pronunciations can be ranked. Pending and approved pronunciations can be edited inline.")?></p>

        <?= $this->Form->create(null, ['url' => ['prefix' => false, 'controller' => 'Pronunciations', 'action' => 'manage', $word->id]]) ?>
        <div class="table-responsive">
			<table class="edit_table pron-manage-table" style="width: 100%; table-layout: fixed;">
				<colgroup>
                    <col style="width: 18%;" />
                    <col style="width: 16%;" />
                    <col style="width: 21%;" />
					<col style="width: 5%;" />
                    <col style="width: 14%;" />
                    <col style="width: 8%;" />
                    <col style="width: 18%;" />
				</colgroup>
                <thead>
                    <tr>
                        <th><?= __('Spelling') ?></th>
                        <th><?= __('Listen') ?></th>
                        <th><?= __('Pronunciation') ?></th>
                        <th class="status-col"><?= __('Status') ?></th>
                        <th class="user-col"><?= __('Username') ?></th>
                        <th><?= __('Ranking') ?></th>
                        <th><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    <?php foreach ($requested_pronunciations as $p): ?>
                        <?php
                            if ('' !== $p->sound_file && !is_null($p->sound_file)) {
                                $audioPlayer = $this->Html->media($p->sound_file, [
                                    'pathPrefix' => 'recordings/',
                                    'controls',
                                    'style' => 'width: 140px; max-width: 100%; height: 32px;',
                                ]);
                            } else {
                                $audioPlayer = '';
                            }

                            $isDenied = (-1 == $p->approved);
                            $isEditable = (0 == $p->approved || 1 == $p->approved);
                            $username = (!empty($p->submitting_user->username)) ? $p->submitting_user->username : 'Submitted by Public';

                            $saveUrl = $this->Url->build(['prefix' => false, 'controller' => 'Pronunciations', 'action' => 'edit', $word->id, $p->id]);
                            $approveUrl = $this->Url->build(['prefix' => false, 'controller' => 'Pronunciations', 'action' => 'approve', $p->id, $word->id]);
                            $denyUrl = $this->Url->build(['prefix' => false, 'controller' => 'Pronunciations', 'action' => 'deny', $word->id, $p->id]);
                            $deleteUrl = $this->Url->build(['prefix' => false, 'controller' => 'Pronunciations', 'action' => 'delete', $p->id, $word->id]);
                        ?>

                        <?= $this->Form->hidden('pronunciations.' . $i . '.id', ['value' => $p->id]) ?>
                        <tr<?= $isDenied ? ' style="opacity: 0.6;"' : '' ?>>
							<td>
                                <?php if ($isEditable): ?>
                                    <?= $this->Form->text('pronunciations.' . $i . '.spelling', [
                                        'value' => $p->spelling,
                                        'style' => 'width: 100%; margin: 0;',
                                    ]) ?>
                                <?php else: ?>
                                    <?= h($p->spelling) ?>
                                <?php endif; ?>
                            </td>
							<td style="overflow: hidden;">
                                <?= $audioPlayer ?>
                            </td>
							<td>
                                <?php if ($isEditable): ?>
                                    <?= $this->Form->text('pronunciations.' . $i . '.pronunciation', [
                                        'value' => $p->pronunciation,
                                        'style' => 'width: 100%; margin: 0;',
                                    ]) ?>
                                <?php else: ?>
                                    <?= h($p->pronunciation) ?>
                                <?php endif; ?>
                            </td>
							<td class="status-col">
                                <span title="<?= h(status_to_words($p->approved)) ?>">
                                    <?= h(status_to_symbol($p->approved)) ?>
                                </span>
                            </td>
							<td class="user-col" style="overflow: hidden; text-overflow: ellipsis;">
                                <span title="<?= h($username) ?>"><?= h($username) ?></span>
                            </td>
							<td style="white-space: nowrap;">
                                <?php if (1 == $p->approved): ?>
                                    <?= $this->Form->number('pronunciations.' . $i . '.display_order', [
                                        'value' => $p->display_order,
                                        'class' => 'recRanking',
									'style' => 'margin: 0; width: 70px;',
                                    ]) ?>
                                <?php endif; ?>
                            </td>
							<td style="white-space: nowrap;">
                                <?php
                                    $options = [];
                                    if ($isEditable) {
                                        $options['post|' . $saveUrl] = 'Save';
                                    }
                                    if (0 == $p->approved) {
                                        $options['post|' . $approveUrl] = 'Approve';
                                    }
                                    if (!$isDenied) {
                                        $options['get|' . $denyUrl] = 'Deny';
                                    }
                                    $options['post|' . $deleteUrl] = 'Delete';

                                    $selectName = 'row_action_' . $p->id;
                                ?>
                                <?= $this->Form->select($selectName, $options, [
                                    'label' => false,
								'style' => 'margin: 0; width: 80px; display: inline-block;',
                                ]) ?>
                                <?= $this->Form->button(__('Go'), [
                                    'type' => 'submit',
                                    'class' => 'button blue nomargin',
                                    'style' => 'margin-left: 6px;',
                                    'onclick' => "var sel=document.getElementsByName('" . $selectName . "')[0]; if(!sel){return false;} var parts=sel.value.split('|'); var mode=parts[0]; var url=parts.slice(1).join('|'); if(mode==='get'){ window.location=url; return false; } if(url.indexOf('/delete/')!==-1){ if(!confirm('Are you sure you want to delete this pronunciation?')){ return false; } } if(url.indexOf('/approve/')!==-1){ if(!confirm('Are you sure you want to approve this pronunciation?')){ return false; } } this.formAction=url; return true;",
                                ]) ?>
                            </td>
                        </tr>
                        <?php $i += 1; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?= $this->Form->button(__('Save Rankings'), ['type' => 'submit', 'class' => 'button blue']) ?>
        <?= $this->Form->end() ?>
    </div>


