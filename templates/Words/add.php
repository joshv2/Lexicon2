<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Word $word
 */

if ('edit' == $controllerName){
    $header = 'Edit';
} else {
    $header = 'New';
}

if (null !== $this->request->getData('spelling') || 'Edit' == $header){
    if(null !== $this->request->getData('spelling')){
        $wordData = $this->request->getData();
    } else {
        $wordData = $word;
    }
}

?>


<section id="main" class="main">
	<div class="page-header group">

        <h2 class="left"><?= $header ?> word</h2>
	</div>
    <div class="c add">
            <?= $this->Form->create($word, ['id' => 'add_form','enctype' => 'multipart/form-data']) ?>

                <?php
                    echo "<div class='form-group'>";
                    echo $this->Form->control('spelling', ['label' => ['text' => 'Most Common Spelling', 'class' => 'req'], 'required' => TRUE]);
                    echo "</div>";
                    echo "<div id='wordexists' style='color:red'></div>";

                    //Alternate Spellings
                    echo "<div class='form-group'>";
                    echo "<label>Alternate Spelling(s)</label>";
                    if ((null !== $this->request->getData('spelling') || 'Edit' == $header) && count($wordData['alternates']) > 0) {

                        $i = 0;

                        while ($i < count($wordData['alternates'])){
                            echo $this->Form->control('alternates.' . $i . '.id',['class' => 'muliplespid', 'data-counter' => $i]);
                            echo $this->Form->control('alternates.' . $i . '.spelling', ['class' => 'multiple']);
                            $i += 1;
                        }
                    } else {
                        echo $this->Form->control('alternates.0.id', ['class' => 'muliplespid', 'data-counter' => '0']);
                        echo $this->Form->control('alternates.0.spelling', ['class' => 'multiple']);
                    }
                    echo "<a class='add'><i class='icon-plus-sign'></i> Add an additional spelling</a>&nbsp;&nbsp;";
				    echo "<a class='remove disabled'><i class='icon-minus-sign'></i> Remove</a>";
                    echo "</div>";


                    ?>

                    <!--Pronunciations-->
                    <div class="form-group">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 0;"></th>
                                    <th style="text-align: left;">Pronuciation (Spelling)</th>
                                    <th style="text-align: left;">Phonetic Spelling</th>
                                    <th style="text-align: left;">Notes</th>
                                    <th style="text-align: left;">Record</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ((null !== $this->request->getData('spelling') || 'Edit' == $header) && count($wordData['pronunciations']) > 0): ?>
                                    <?php $i = 0;
                                          while ($i < count($wordData['pronunciations'])){ ?>
                                          
                                        <!--Produce rows-->
                                        <tr class="table-row" data-counter=<?= $i; ?>>
                                            <td style="width:0;">
                                                <?=  $this->Form->control('pronunciations.' . $i . '.id',['label' => FALSE, 'class' => 'muliplespid']);?>
                                            </td>
                                            <td>
                                                <?= $this->Form->control('pronunciations.' . $i . '.spelling', ['label' => FALSE, 'class' => 'muliplespsp']);?>
                                            </td>
                                            <td>
                                                <?= $this->Form->control('pronunciations.' . $i . '.pronunciation', ['label' => FALSE, 'class' => 'muliplespsp']);?>
                                            </td>
                                            <td>
                                                <?= $this->Form->control('pronunciations.' . $i . '.notes', ['label' => FALSE, 'class' => 'muliplespsp']);?>
                                            </td>
                                            <td style="vertical-align: top;">
                                                <span class="record-success" style="display: none;">Recorded <i class="icon-ok"></i></span>
                                                <?= $this->Form->button('Record', ['class' => 'btn-record button', 'id' => 'record']);?>
                                                <?= $this->Form->control('soundfile' . $i, [
                                                    'class' => 'recording-input',
                                                    'type' => 'file',
                                                    'style' => 'display:none',
                                                    'label' => FALSE
                                                ]); ?>
                                            </td>

                                        </tr>
                                        <!--End Produce rows-->
                                        <?php echo $this->Form->hidden('pronunciations.' . $i. '.sound_file'); ?>
                                    <?php $i += 1;
                                          } ?>      

                                <?php else: ?>
                                    <!--Produce rows-->
                                    <tr class="table-row" data-counter="0">
                                        <td style="width:0;">
                                            <?=  $this->Form->control('pronunciations.0.id',['label' => FALSE, 'class' => 'muliplespid']);?>
                                        </td>
                                        <td>
                                            <?= $this->Form->control('pronunciations.0.spelling', ['label' => FALSE, 'class' => 'muliplespsp']);?>
                                        </td>
                                        <td>
                                            <?= $this->Form->control('pronunciations.0.pronunciation', ['label' => FALSE, 'class' => 'muliplespsp']);?>
                                        </td>
                                        <td>
                                            <?= $this->Form->control('pronunciations.0.notes', ['label' => FALSE, 'class' => 'muliplespsp']);?>
                                        </td>
                                        <td style="vertical-align: top;">
                                            <span class="record-success" style="display: none;">Recorded <i class="icon-ok"></i></span>
                                            <?= $this->Form->button('Record', ['class' => 'btn-record button', 'id' => 'record']);?>
                                            <?= $this->Form->control('soundfile0', [
                                                'class' => 'recording-input',
                                                'type' => 'file',
                                                'style' => 'display:none',
                                                'label' => FALSE
                                            ]); ?>
                                        </td>
                                    </tr>
                                    <!--End Produce rows-->
                                <?php endif; ?>

                                <tr>
                                    <td colspan="5">
                                        <a class='add-row'><i class='icon-plus-sign'></i> Add an additional pronunciation</a>&nbsp;&nbsp;
                                        <a class='remove-row disabled'><i class='icon-minus-sign'></i> Remove</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    
                    //Definitions
                    echo "<div class='form-group'>";
                    echo "<label>Definition(s)</label>";
                    if ((null !== $this->request->getData('spelling') || 'Edit' == $header) && count($wordData['definitions']) > 0) { //true == $word->{'hasErrors'} || 
                        if(null !== $this->request->getData('spelling')){
                            $arrayLocation = 'defintion';

                        } else {
                            $arrayLocation = 'definition_json';
                        }
                        
                        $i = 0;
                        while ($i < count($wordData['definitions'])){
                            echo $this->Form->control('definitions.' . $i. '.id',['class' => 'muliplespid', 'data-counter' => $i]);
                            
                            //For entries with no presubmitted JSON
                            if ('' == $wordData['definitions'][$i][$arrayLocation]){
                                $finalInsert = '{"ops":[{"insert":"' . str_replace('"', '\"', $wordData['definitions'][$i]['definition']) . '\n"}]}';
                            } else {
                                $finalInsert = $wordData['definitions'][$i][$arrayLocation];
                            }
                            echo $this->Form->hidden('definitions.' . $i. '.definition', ['id' => 'definition'. $i, 'value' => $finalInsert]);
                            echo "<div class='editor-container'><div id='editor-definition" . $i ."'></div></div>";
                            $i += 1;
                        }
                    } else {
                        echo $this->Form->control('definitions.0.id',['class' => 'muliplespid', 'data-counter' => '0']);
                        echo $this->Form->hidden('definitions.0.definition', ['id' => 'definition0']);
                        echo "<div class='editor-container'><div id='editor-definition0'></div></div>";
                    }
                    
                    echo "<a class='add-editor'><i class='icon-plus-sign'></i> Add an additional definition</a>&nbsp;&nbsp;";
				    echo "<a class='remove-editor disabled'><i class='icon-minus-sign'></i> Remove</a>";
                    echo  "</div>";
                    
                    //Sentences
                    echo "<div class='form-group'>";
                    echo "<label>Example Sentence(s)</label>";
                    if ((null !== $this->request->getData('spelling') || 'Edit' == $header) && count($wordData['sentences']) > 0) { 
                        if(null !== $this->request->getData('spelling')){
                            $arrayLocation = 'sentence';

                        } else {
                            $arrayLocation = 'sentence_json';
                        }
                        
                        $i = 0;
                        while ($i < count($wordData['sentences'])){
                            //For entries with no presubmitted JSON
                            if ('' == $wordData['sentences'][$i][$arrayLocation]){
                                $finalInsert = '{"ops":[{"insert":"' . str_replace('"', '\"', $wordData['sentences'][$i]['sentence']) . '\n"}]}';
                            } else {
                                $finalInsert = $wordData['sentences'][$i][$arrayLocation];
                            }
                            echo $this->Form->control('sentences.' . $i . '.id',['class' => 'muliplespid', 'data-counter' => $i]);
                            echo $this->Form->hidden('sentences.' . $i . '.sentence', ['id' => 'sentences' . $i, 'value' => $finalInsert]);
                            echo "<div class='editor-container'><div id='editor-sentences" . $i . "'></div></div>";
                            
                            $i += 1;
                        }
                    } else {
                        echo $this->Form->control('sentences.0.id',['class' => 'muliplespid', 'data-counter' => '0']);
                        echo $this->Form->hidden('sentences.0.sentence', ['id' => 'sentences0']);
                        echo "<div class='editor-container'><div id='editor-sentences0'></div></div>";
                    }
                    
                    echo "<a class='add-editor'><i class='icon-plus-sign'></i> Add an additional sentence</a>&nbsp;&nbsp;";
				    echo "<a class='remove-editor disabled'><i class='icon-minus-sign'></i> Remove</a>";
                    echo "</div>";


                    echo "<div class='form-group left'>";
                    echo $this->Form->control('origins._ids', ['options' => $origins, 'label' => 'Language(s) of Origin', 'style' => 'width:100%;display:block;']);
                    echo "<p class='mini'>Hold down Ctrl to select more than one option, Ctrl-click again to deselect</p>";
                    echo "</div>";

                    echo "<div class='form-group clear'>";
                    echo "<p class='notes'>Etymology of the word</p>";
                    if ((null !== $this->request->getData('spelling') || 'Edit' == $header) && '' != $wordData['etymology']) { 
                        if(null !== $this->request->getData('spelling')){
                            $arrayLocation = 'etymology';

                        } else {
                            $arrayLocation = 'etymology_json';
                        }
                        
                        //For entries with no presubmitted JSON
                        if ('' == $wordData['etymology_json']){
                            $finalInsert = '{"ops":[{"insert":"' . str_replace('"', '\"', $wordData['etymology']) . '\n"}]}';
                        } else {
                            $finalInsert = $wordData[$arrayLocation];
                        }
                        echo $this->Form->hidden('etymology', ['id' => 'etymology', 'value' => $finalInsert]);
                        echo $this->Form->label('etymology');
                    } else {
                        echo $this->Form->hidden('etymology', ['id' => 'etymology']);
                        echo $this->Form->label('etymology');
                    }
                    // echo $this->Form->control('etymology', ['label' => false]);
                    echo "<div id='editor-etymology'></div>";
                    echo "</div>";

                    //echo $this->Form->control('approved');
                    //echo $this->Form->control('language_id');
                    echo $this->Form->hidden('language_id', ['value' => 1]);
                    
                    //echo $this->Form->control('dictionaries._ids', ['options' => $dictionaries]);
                    
                    
                    echo "<div class='form-group left'>";
                    echo $this->Form->control('types._ids', ['options' => $types, 'label' => 'Who Uses This', 'style' => 'width:100%;display:block;']);
                    echo "<p class='mini'>Hold down Ctrl to select more than one option, Ctrl-click again to deselect</p>";
                    echo "</div>";
                    
                    echo "<div class='form-group right'>";
                    echo $this->Form->control('regions._ids', ['options' => $regions, 'label' => 'Regions in Which the Word is Used', 'style' => 'width:100%;display:block;']);
                    echo "<p class='mini'>Hold down Ctrl to select more than one option, Ctrl-click again to deselect</p>";
                    echo "</div>";

                    echo "<div class='form-group clear'>";
                    if ((null !== $this->request->getData('spelling') || 'Edit' == $header) && '' != $wordData['etymology']) { 
                        if(null !== $this->request->getData('spelling')){
                            $arrayLocation = 'notes';

                        } else {
                            $arrayLocation = 'notes_json';
                        }

                        //For entries with no presubmitted JSON
                        if ('' == $wordData['notes_json']){
                            $finalInsert = '{"ops":[{"insert":"' . str_replace('"', '\"', $wordData['notes']) . '\n"}]}';
                        } else {
                            $finalInsert = $wordData[$arrayLocation];
                        }
                        echo $this->Form->hidden('notes', ['id' => 'notes', 'value' => $finalInsert]);
                        echo $this->Form->label('notes');
                    } else {
                        echo $this->Form->hidden('notes', ['id' => 'notes']);
                        echo $this->Form->label('notes');
                    }
                    echo "<p class='notes'>Pronunciation, context, or anything else you want website visitors to know about this entry</p>";
                    // echo $this->Form->control('notes', ['label' => false]);
                    echo "<div id='editor-notes'></div>";
                    echo "</div>";

                    if ($this->Identity->isLoggedIn()){
                        echo $this->Form->hidden('user_id', ['value' => $this->Identity->get('id')]);
                        echo $this->Form->hidden('approved', ['value' => TRUE]);
                    } else {
                        echo "<p class='m2'>Your name and email will not appear on the website. We require it in case we have questions about your entry. If you add many words to the lexicon, we will give you the option of being listed as one of the <a href='https://jel.jewish-languages.org/about' target='_new'>Top Word Contributors</a>. Other than that, you will <strong>NOT</strong> be contacted or placed on any email lists.</p>";
                        echo "<div class='form-group'>";
                        echo $this->Form->control('full_name', ['label' => ['text' => 'Your Name', 'class' => 'req']]);
                        echo "</div>";

                        echo "<div class='form-group'>";
                        echo $this->Form->control('email', ['label' => ['text' => 'Your Email Address', 'class' => 'req']]);
                        echo "</div>";
                        echo $this->Form->hidden('approved', ['value' => FALSE]);
                        echo "<div class='g-recaptcha' data-sitekey='" . $recaptcha_user . "'></div>";
                    }
                    echo "<p>Double check your submission!</p>";
                ?>

            <?= $this->Form->button(__('Submit'), ['class' => "button blue", 'id'=> "submitbutton"]) ?>
            <?= $this->Form->end() ?>
            <?php 
                if (isset($wordData) && 0 == $wordData['approved']) {
                echo $this->Form->postLink(
                    'Approve Word',
                    ['prefix' => false, 'controller' => 'Words', 'action' => 'approve', $wordData['id']],
                    ['confirm' => 'Are you sure?']);
                }?>
    </div>
</section>

<?php if ('Edit' == $header){ 
    foreach ($wordData['suggestions'] as $suggestion) {
        echo "Suggestions:" . $suggestion['suggestion'];
    }

} ?>

<script>
        window.addEventListener('DOMContentLoaded', () => {
            const recordButton = document.getElementById('record');
            window.InitializeRecorder(recordButton);
        });
    </script>

<script>
$(function(){
    $('[name="spelling"]').blur(function(){
        $.ajax({
            type: "POST",
            url: "/words/checkforword",
            data: {
                spelling: $('[name="spelling"]').val()
            },
            headers: {
                'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
            },
            success: function(response) {
                var newData = response;
                
                //alert(newData.response.spelling);
                if (newData.response.spelling == false) {
                    $('#wordexists').text("This word already exists in the lexicon or is being evaluated.");
                    $("#submitbutton").prop('disabled', true);
                } else {
                    $('#wordexists').text("");
                    $("#submitbutton").prop('disabled', false);
                }
            }
        })
    });
})
</script>