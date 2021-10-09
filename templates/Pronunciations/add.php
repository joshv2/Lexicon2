<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pronunciation $pronunciation
 */
?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="pronunciations form content">
            <?= $this->Form->create($pronunciation), ['id' => 'add_form','enctype' => 'multipart/form-data']) ?>
            <fieldset>
                <legend><?= __('Add Pronunciation for ' . $word->spelling) ?></legend>
                <div class="form-group">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 0;"></th>
                                    <th style="text-align: left;"><?=__("Pronuciation (Spelling)")?></th>
                                    <th style="text-align: left;"><?=__("Phonetic Spelling")?></th>
                                    <th style="text-align: left;"><?=__("Record")?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-row" data-counter="0">
                                    <td style="width:0;">
                                        <?=  $this->Form->control(__('id')_,['label' => FALSE, 'class' => 'muliplespid']);?>
                                    </td>
                                    <td>
                                        <?= $this->Form->control(__('spelling'), ['label' => FALSE, 'class' => 'muliplespsp']);?>
                                    </td>
                                    <td>
                                        <?= $this->Form->control(__('pronunciation'), ['label' => FALSE, 'class' => 'muliplespsp']);?>
                                    </td>
                                    <td style="vertical-align: top;">
                                        <span class="record-success" style="display: none;">Recorded <i class="icon-ok"></i></span>
                                        <?= $this->Form->button(__('Record'), ['class' => 'btn-record button', 'id' => 'record']);?>
                                        <?= $this->Form->control(__('soundfile0'), [
                                            'class' => 'recording-input',
                                            'type' => 'file',
                                            'style' => 'display:none',
                                            'label' => FALSE
                                        ]); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php
                    echo $this->Form->hidden(__('word_id'), ['value' => $this->request->getParam('pass')[0]]);
                    //echo $this->Form->control('word_id', ['options' => $words]);
                    //echo $this->Form->control('spelling');
                    //echo $this->Form->control('sound_file');
                    //echo $this->Form->control('pronunciation');
                    //echo $this->Form->control('notes');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
