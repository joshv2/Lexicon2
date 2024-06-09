<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pronunciation $pronunciation
 */
?>

<p class="landscape">This form is best viewed in landscape mode.</p>
<div>
    <div class="column-responsive column-80">
        <div class="pronunciations form content">
            <?= $this->Form->create($pronunciation, ['id' => 'add_form','enctype' => 'multipart/form-data']) ?>
            <fieldset>
                <legend><?= __('Add Pronunciation for ') . $word->spelling ?></legend>
                <div class='readingSentence'></div>
                <div class="form-group">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 0;"></th>
                                    <th style="text-align: left;"><?=__("Pronuciation (Spelling)")?></th>
                                    <th style="text-align: left;"><?=__("Phonetic Spelling")?></th>
                                    <th style="text-align: center;" colspan=2><?=__("Record")?></th>
                                    <!--<th style="text-align: left;"></th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-row" data-counter="0">
                                    <td style="width:0;">
                                        <?=  $this->Form->control('id',['label' => FALSE]);?>
                                    </td>
                                    <td>
                                        <?= $this->Form->control(__('spelling'), ['label' => FALSE]);?>
                                    </td>
                                    <td>
                                        <?= $this->Form->control(__('pronunciation'), ['label' => FALSE]);?>
                                    </td>
                                    <td>
                                        <input type="radio" name="option" id="showButton" checked>
                                        <label for="showButton">Record on Your Device</label>
                                        <br/>
                                        <input type="radio" name="option" id="showUploadBox">
                                        <label for="showUploadBox">Upload a Recording</label>
                                        
                                    </td>
                                    <td style="vertical-align: top;" id="recordcell"> 
                                        <div id="buttonContainer">
                                            <span class="record-success" style="display: none;">Recorded <i class="fa-solid fa-check"></i></span>    
                                            <button class="btn-record button" id="record" type="submit">Click to Record</button>
                                            <?= $this->Form->control(__('soundfile0'), [
                                                'class' => 'recording-input',
                                                'type' => 'file',
                                                'style' => 'display:none',
                                                'label' => FALSE
                                            ]) ?>
                                        </div>

                                        <div id="uploadBoxContainer" style="display:none">
                                            <input type="file" name="soundfile1" id="soundfile1" style="width: 176px;" accept=".mp3">
                                        </div>    
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <a href="/notes/#bottom">View tips on making a high-quality recording.</a>
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
<?= $this->Html->script('uploadtoggle')."\n";?>
<?= $this->Html->script('detectios')."\n";?>
