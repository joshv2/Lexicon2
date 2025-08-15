<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Word $word
 */

if ('edit' == $controllerName){
    $header = __('Edit');
} else {
    $header = __('New');
}

if (null !== $this->request->getData('spelling') || 'edit' == $controllerName){
    if(null !== $this->request->getData('spelling')){
        $wordData = $this->request->getData();
    } else {
        $wordData = $word;
    }
}

?>

<p class="landscape"><?= __("This form is best viewed in landscape mode.")?></p>

<section id="main" class="main">
	<div class="page-header group">

        <h2 class="left"><?= $header ?> <?= __('word') ?></h2>
	</div>
    <div class="c add">
            <?= $this->Form->create($word, ['id' => 'add_form','enctype' => 'multipart/form-data']) ?>

                <?php
                    echo "<div class='form-group'>";
                    echo $this->Form->control('spelling', ['label' => ['text' => __('Most Common Spelling'), 'class' => 'req'], 'required' => TRUE]);
                    echo "<div id='wordexists' style='color:red'></div>";
                    echo "</div>";
                    

                    //Alternate Spellings
                    echo "<div class='form-group'>";
                    echo "<label>" . __('Alternate Spelling(s)') . "</label>";
                    if ((null !== $this->request->getData('spelling') || 'edit' == $controllerName) && count($wordData['alternates']) > 0) {

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
                    echo "<a class='add'><i class='fa fa-plus' aria-hidden='true'></i> " . __('Add an additional spelling') . "</a>&nbsp;&nbsp;";
				    echo "<a class='remove disabled'><i class='fa fa-minus' aria-hidden='true'></i> " . __('Remove') . "</a>";
                    echo "</div>";


                    ?>

                    <!--Pronunciations-->
                    <div class="form-group" id='alternates'>
                        <?php if('edit' == $controllerName) {
                            echo '<p style="color:red">Do not add pronunciations on this page, please use ' . $this->Html->link('Add a Pronunciation', '/pronunciations/add/' . $word->id) . '</p>';
                        } ?>
                    
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 0;"></th>
                                    <th style="text-align: left;"><?=__("Spelling")?></th>
                                    <th style="text-align: left;"><?=__("Phonetic Spelling ")?><span class="tooltip" title="<?= __("Using dashes and capital letters, indicate syllable and stress, e.g., 'te-SHOO-vuh' vs. 'tshoo-VAH'")?>"><i class="fa-solid fa-circle-info"></i></span></th>
                                    <th style="text-align: left;"><?=__("Record")?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ((null !== $this->request->getData('spelling') || 'edit' == $controllerName) && count($wordData['pronunciations']) > 0): ?>
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
                                            <td style="vertical-align: top;">
                                                <span class="record-success" style="display: none;"> <?= __('Recorded') ?> <i class="fa-solid fa-check"></i></span>
                                                    <?= $this->Form->button(
                                                        __('Record'),
                                                        [
                                                            'class' => 'btn-record button' . ('edit' == $controllerName ? ' btn-record-disabled' : ''),
                                                            'id' => 'record',
                                                            'disabled' => ('edit' == $controllerName)
                                                        ]
                                                    );?>                                                
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
                                            <?=  $this->Form->control('pronunciations.0.id',['label' => FALSE]);?>
                                        </td>
                                        <td>
                                            <?= $this->Form->control('pronunciations.0.spelling', ['label' => FALSE]);?>
                                        </td>
                                        <td>
                                            <?= $this->Form->control('pronunciations.0.pronunciation', ['label' => FALSE]);?>
                                        </td>
                                        <td style="vertical-align: top;" id="recordcell">
                                            <span class="record-success" style="display: none;">Recorded <i class="fa-solid fa-check"></i></span>
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
                                    <td colspan="3">
                                        <a class='add-row'><i class="fa fa-plus" aria-hidden="true"></i> <?=__("Add an additional pronunciation")?></a>&nbsp;&nbsp;
                                        <a class='remove-row disabled'><i class='fa fa-minus' aria-hidden='true'></i> <?=__("Remove")?></a>
                                        
                                    </td>
                                    <td>
                                    <?php 
                                    if ('edit' == $controllerName && count($wordData['pronunciations']) > 0) { 
                                        if ($pronunciationCount === 0) {
                                            echo __('No Pronunciations Recorded - ' . $this->Html->link('Add a Pronunciation', '/pronunciations/add/' . $word->id));
                                        } else {
                                            echo $this->Html->link(__('Review/Arrange Recordings') . ' (' . $pronunciationCount . ')', 
                                                                        ['controller' => 'Pronunciations', 
                                                                         'action' => 'manage', $wordData['id']], 
                                                                        ['class' => 'btn-record2 button', 
                                                                         'title' => 'Click here to listen to submitted recordings and to change the order in which the recordings appear on the word page']);
                                        } 
                                    } else echo '';?>    
                                    
                                        </td>
                                    <td>
                                        
                                        </td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    echo "<hr>";
                    //Definitions
                    echo "<div class='form-group' id='pronunciationsgroup'>";
                    echo "<label>" . __('Definition(s)') . "</label>";
                    echo "<a class='add-editor'><i class='fa fa-plus' aria-hidden='true'></i> " . __('Add an additional definition') . "</a>";
                    if ((null !== $this->request->getData('spelling') || 'edit' == $controllerName) && count($wordData['definitions']) > 0) { //true == $word->{'hasErrors'} || 
                        if(null !== $this->request->getData('spelling')){
                            $arrayLocation = 'defintion';

                        } else {
                            $arrayLocation = 'definition_json';
                        }
                        
                        $i = 0;
                        while ($i < count($wordData['definitions'])){
                            echo "<div id='defeditor" . $wordData['definitions'][$i]['id'] . "' data-counter='". $i ."'>";
                            echo $this->Form->control('definitions.' . $i. '.id',['class' => 'muliplespid']);
                            
                            //For entries with no presubmitted JSON
                            if ('' == $wordData['definitions'][$i][$arrayLocation]){
                                $finalInsert = '{"ops":[{"insert":"' . str_replace(':', "\u003A", str_replace(",", "\u002C", str_replace("!", "\u0021", str_replace(";", "\u003B", str_replace(array("\n", "\r", "\r\n"), "\\n", str_replace('"', '\"', str_replace("\\", "\\\\", $wordData['definitions'][$i]['definition']))))))) . '\n"}]}';
                            } else {
                                $finalInsert = $wordData['definitions'][$i][$arrayLocation];
                            }
                            echo $this->Form->hidden('definitions.' . $i. '.definition', ['id' => 'definition'. $i, 'value' => $finalInsert]);
                            echo "<div class='editor-container'><div id='editor-definition" . $i ."'></div></div>";
                            

                            echo "<div class='delete2'><a class='deletelink button red' id=def" .  $wordData['definitions'][$i]['id'] . "-" . $wordData['id'] . " href='#'><i class='fas fa-arrow-left'></i> Delete Definition</a></div></div>";
                    

                            $i += 1;
                            
                        }
                    } else {
                        echo "<div id='defeditor0' data-counter='0'>";
                        echo $this->Form->control('definitions.0.id',['class' => 'muliplespid', 'data-counter' => '0']);
                        echo $this->Form->hidden('definitions.0.definition', ['id' => 'definition0']);
                        echo "<div class='editor-container'><div id='editor-definition0'></div></div></div>";
                    }
                    
                    echo  "</div>";
                                       
                    echo "<hr>";
                    
                    //Sentences
                    echo "<div class='form-group' id='sentencesgroup'>";
                    echo "<label>" . __('Example Sentence(s)') . "</label>";
                    echo "<a class='add-editor2'><i class='fa fa-plus' aria-hidden='true'></i> " . __('Add an additional sentence') . "</a>";
                    if ((null !== $this->request->getData('spelling') || 'edit' == $controllerName) && count($wordData['sentences']) > 0) { 
                        if(null !== $this->request->getData('spelling')){
                            $arrayLocation = 'sentence';

                        } else {
                            $arrayLocation = 'sentence_json';
                        }
                        
                        $i = 0;
                        while ($i < count($wordData['sentences'])){
                            echo "<div id='senteditor" . $wordData['sentences'][$i]['id'] . "' data-counter='". $i ."'>";
                            echo $this->Form->control('sentences.' . $i. '.id',['class' => 'muliplespid']);
                            
                            //For entries with no presubmitted JSON
                            if ('' == $wordData['sentences'][$i][$arrayLocation]){
                                $finalInsert = '{"ops":[{"insert":"' . str_replace(':', "\u003A", str_replace(",", "\u002C", str_replace("!", "\u0021", str_replace(";", "\u003B", str_replace(array("\n", "\r", "\r\n"), "\\n", str_replace('"', '\"', str_replace("\\", "\\\\", $wordData['sentences'][$i]['sentence']))))))) . '\n"}]}';
                            } else {
                                $finalInsert = $wordData['sentences'][$i][$arrayLocation];
                            }
                            //echo $this->Form->control('sentences.' . $i . '.id',['class' => 'muliplespid', 'data-counter' => $i]);
                            echo $this->Form->hidden('sentences.' . $i . '.sentence', ['id' => 'sentences' . $i, 'value' => $finalInsert]);
                            echo "<div class='editor-container'><div id='editor-sentences" . $i . "'></div></div>";
                            
                            echo "<div class='delete2'><a class='deletelink button red' id=sen" .  $wordData['sentences'][$i]['id'] . "-" . $wordData['id'] . " href='#'><i class='fas fa-arrow-left'></i> Delete Sentence</a></div></div>";

                            $i += 1;
                        }
                    } else {
                        echo "<div id='senteditor0' data-counter='0'>";
                        echo $this->Form->control('sentences.0.id',['class' => 'muliplespid', 'data-counter' => '0']);
                        echo $this->Form->hidden('sentences.0.sentence', ['id' => 'sentences0']);
                        echo "<div class='editor-container'><div id='editor-sentences0'></div></div></div>";
                    }
                    
                    echo  "</div>";
                    echo "<hr>";

                    if (1 == $sitelang->hasOrigins){
                    echo "<div class='form-group left'>";
                    $neworigins = [];
                    foreach ($origins as $key => $origin){
                        $neworigins[$key] = __($origin);
                    }
                    echo $this->Form->control('origins._ids', ['options' => $neworigins, 'label' => __('Language(s) of Origin'), 'style' => 'width:100%;display:block;', 'size' => '7']);
                    echo "<p class='mini'>" . __('Hold down Ctrl to select more than one option, Ctrl-click again to deselect') ."</p>";
                    echo "</div>";
                    }

                    

                    //echo "<div class='form-group left'>";
                    //echo $this->Form->label('Add an Origin');
                    //echo $this->Form->text('extraOrigin');
                    //echo "</div>";
                    if ('superuser' == $this->request->getSession()->read('Auth.role') && 1 == $sitelang->hasDictionaries){
                        echo "<div class='form-group right'>";
                        echo $this->Form->control('dictionaries._ids', ['options' => $dictionaries, 'label' => __('Dictionaries'), 'style' => 'width:100%;display:block;', 'size' => '7']);
                        echo "<p class='mini'>" . __('Hold down Ctrl to select more than one option, Ctrl-click again to deselect') ."</p>";
                        echo "</div>";
                    }
                    echo "<div class='form-group clear'>";
                    
                    echo "<div class='form-group-origins-other'>";
                    echo $this->Form->control('origin_other_entry', ['label' => ['text' => __('Enter other origins separated by semicolon'), 'class' => 'req']]);
                    echo "</div>";

                    if ((null !== $this->request->getData('spelling') || 'edit' == $controllerName) && '' != $wordData['etymology']) { 
                        if(null !== $this->request->getData('spelling')){
                            $arrayLocation = 'etymology';

                        } else {
                            $arrayLocation = 'etymology_json';
                        }
                        
                        //For entries with no presubmitted JSON
                        if ('' == $wordData['etymology_json']){
                            $finalInsert = '{"ops":[{"insert":"' . str_replace(':', "\u003A", str_replace(",", "\u002C", str_replace("!", "\u0021", str_replace(";", "\u003B", str_replace(array("\n", "\r", "\r\n"), "\\n", str_replace('"', '\"', str_replace("\\", "\\\\", $wordData['etymology']))))))) . '\n"}]}';
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
                    echo $this->Form->hidden('language_id', ['value' => $sitelang->id]);
                    
                    //echo $this->Form->control('dictionaries._ids', ['options' => $dictionaries]);
                    
                    if (1 == $sitelang->hasTypes){
                    echo "<div class='form-group left'>";
                    $newtypes = [];
                    foreach ($types as $key => $type){
                        $newtypes[$key] = __($type);
                    }
                    echo $this->Form->control('types._ids', ['options' => $newtypes, 'label' => __('Who Uses This'), 'style' => 'width:100%;display:block;']);
                    echo "<p class='mini'>" . __('Hold down Ctrl (command on Mac) to select more than one option, Ctrl/command-click again to deselect') . "</p>";
                    echo "</div>";
                    }
                    if (1 == $sitelang->hasRegions){
                    echo "<div class='form-group right'>";
                    $newregions = [];
                    foreach ($regions as $key => $region){
                        $newregions[$key] = __($region);
                    }
                    echo $this->Form->control('regions._ids', ['options' => $newregions, 'label' => __('Regions in Which the Word is Used'), 'style' => 'width:100%;display:block;']);
                    echo "<p class='mini'>" . __('Hold down Ctrl (command on Mac) to select more than one option, Ctrl/command-click again to deselect') . "</p>";
                    echo "</div>";
                    }
                    //echo "<div class='form-group left'>";
                    //echo $this->Form->label('Add a Type');
                    //echo $this->Form->text('extraType');
                    //echo "</div>";

                    //echo "<div class='form-group right'>";
                    //echo $this->Form->label('Add a Region');
                    //echo $this->Form->text('extraRegion');
                    //echo "</div>";
                    echo "<div class='form-group clear'>";
                    echo "<div class='form-group-types-other'>";
                        echo $this->Form->control('type_other_entry', ['label' => ['text' => __('Enter other groups separated by semicolon'), 'class' => 'req']]);
                    echo "</div>";
                    
                    if ((null !== $this->request->getData('spelling') || 'edit' == $controllerName) && '' != $wordData['notes']) { 
                        if(null !== $this->request->getData('spelling')){
                            $arrayLocation = 'notes';

                        } else {
                            $arrayLocation = 'notes_json';
                        }

                        //For entries with no presubmitted JSON
                        if ('' == $wordData['notes_json']){
                            //$finalInsert = '{"ops":[{"insert":"' . str_replace(",", "\u002C", str_replace("!", "\u0021", str_replace(";", "\u003B", str_replace(array("\n", "\r", "\r\n"), "\\n", str_replace('"', '\"', $wordData['notes']))))) . '\n"}]}';
                            $finalInsert = '{"ops":[{"insert":"' . str_replace(':', "\u003A", str_replace(",", "\u002C", str_replace("!", "\u0021", str_replace(";", "\u003B", str_replace(array("\n", "\r", "\r\n"), "\\n", str_replace('"', '\"', str_replace("\\", "\\\\", $wordData['notes']))))))) . '\n"}]}';
                        } else {
                            $finalInsert = $wordData[$arrayLocation];
                        }
                        echo $this->Form->hidden('notes', ['id' => 'notes', 'value' => $finalInsert]);
                        echo $this->Form->label('notes');
                    } else {
                        echo $this->Form->hidden('notes', ['id' => 'notes']);
                        echo $this->Form->label('notes');
                    }
                    echo "<p class='notes'>" . __('Anything else you want website visitors to know about this entry') . "</p>";
                    // echo $this->Form->control('notes', ['label' => false]);
                    echo "<div id='editor-notes'></div>";
                    echo "</div>";

                    if ($this->Identity->isLoggedIn() && 'superuser' == $this->request->getSession()->read('Auth.role')){
                        echo $this->Form->hidden('user_id', ['value' => $this->Identity->get('id')]);
                        echo $this->Form->hidden('approved', ['value' => TRUE]);
                    } elseif ($this->Identity->isLoggedIn()) {
                        echo $this->Form->hidden('user_id', ['value' => $this->Identity->get('id')]);
                        echo $this->Form->hidden('approved', ['value' => FALSE]);
                    }  else {
                        echo "<p class='m2'>" . __('Your name and email will not appear on the website. We require it in case we have questions about your entry. If you add many words to the lexicon, we will give you the option of being listed as one of the') . '<a href="/about" target="_new"> ' . __('Top Word Contributors') . "</a>. " . __('Other than that, you will') . "<strong> " . __('NOT') . "</strong> " . __('be contacted or placed on any email lists') . ".</p>";
                        echo "<div class='form-group'>";
                        echo $this->Form->control('full_name', ['label' => ['text' => __('Your Name'), 'class' => 'req']]);
                        echo "</div>";

                        echo "<div class='form-group'>";
                        echo $this->Form->control('email', ['label' => ['text' => __('Your Email Address'), 'class' => 'req']]);
                        echo "</div>";
                        echo $this->Form->hidden('approved', ['value' => FALSE]);
                        echo "<div class='g-recaptcha' data-sitekey='" . $recaptcha_user . "'></div>";
                    }
                    echo "<p>" . __('Double check your submission!') . "</p>";
                ?>

            <?= $this->Form->button(__('Submit'), ['class' => "button blue", 'id'=> "submitbutton"]) ?>
            <?= $this->Form->end() ?>
            <?php 
                if (isset($wordData) && 0 == $wordData['approved'] && in_array($this->request->getSession()->read('Auth.role'), ['superuser', 'moderator'])) {
                echo $this->Form->postLink(__(
                    'Approve Word'),
                    ['prefix' => false, 'controller' => 'Words', 'action' => 'approve', $wordData['id']],
                    ['confirm' => 'Are you sure?'])
                 . ' ' . $this->Form->postLink(__(
                    'Delete Word'),
                    ['prefix' => false, 'action' => 'delete', $wordData['id']],
                    ['confirm' => __('Are you sure?'), 'style' => 'color:red']);
                  } ?>
    </div>
</section>


<?php if ('edit' == $controllerName){ 
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
<?php if ('edit' !== $controllerName): ?>
<script>
$(function(){
    $('[name="spelling"]').blur(function(){
        $.ajax({
            type: "POST",
            url: "words/checkforword",
            data: {
                spelling: $('[name="spelling"]').val(),
                language_id: $('[name="language_id"]').val()
            },
            headers: {
                'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
            },
            success: function(response) {
                var newData = response;
                
                //alert(newData.response);
                if (newData.spelling == false) {
                    $('#wordexists').text('<?= __("This word already exists in the lexicon or is being evaluated.") ?>');
                    $("#submitbutton").prop('disabled', true);
                } else {
                    $('#wordexists').text("");
                    $("#submitbutton").prop('disabled', false);
                }
            }
        })
    });
});

</script>
<?php endif; ?>
<script>
    $(function(){
        $(".deletelink").click(function(event){
            event.preventDefault();
            if(confirm('Do you want to delete this pronunciation?')){
                var defidstart = event.target.id;
                var deletetype = defidstart.substring(0,3);
                if(deletetype == 'def'){
                    var nodefprefix = defidstart.replace('def','');
                    var path = 'definitions';
                    var elementid = '#defeditor'; }
                else {
                    var nodefprefix = defidstart.replace('sen','');
                    var path = 'sentences';
                    var elementid = '#senteditor';
                }
                var defid = nodefprefix.split("-");
                console.log(defid[0]);
                $.ajax({
                    type: "POST",
                    url: "/" + path + "/ajaxdelete/" + defid[0],
                    /*data: {
                        spelling: $('[name="spelling"]').val()
                    },*/
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
                    },
                    success: function(response) {
                        var newData = response;
                        
                        //alert(newData.response.spelling);
                        if (newData.response.success == 1) {
                            console.log('value deleted');
                            $(elementid + defid[0]).remove();
                        } else {
                            console.log('value not deleted');
                            console.log(newData.response);
                        }
                    }
                })
            } else {
                console.log('No');
            }
        });
    });
</script>
<script type="text/javascript">
	if(iOS() == true){
		$("#recordcell").empty();
        $("#recordcell").append("Recording is not available on iOS at this time.")
	}
</script>   
<script>
    $(document).ready(showOtherBoxLoad);
    $(document).ready(showOtherBoxLoadR);
    $("#origins-ids").on('change', showOtherBox);
    $("#types-ids").on('change', showOtherBoxR);

    function showOtherBoxLoad() {
        if($("#origins-ids").val().indexOf("999") >= 0) {
            $(".form-group-origins-other").show();
            $("#origin-other-entry").attr('name', 'origin_other_entry' + '<?php echo $specialother; ?>');
            $("#origin-other-entry").attr('required','required');
            $("#origin-other-entry").val('<?php echo $specialothervalue; ?>');
        } else  {
            $(".form-group-origins-other").hide();           
        }
    }


    function showOtherBox() {
        if($("#origins-ids").val().indexOf("999") >= 0) {
            $(".form-group-origins-other").show(); 
            $("#origin-other-entry").attr('required','required');        
        } else  {
            $(".form-group-origins-other").hide();
            $("#origin-other-entry").removeAttr('required');           
        }
    }
    

    

    function showOtherBoxLoadR() {
        if($("#types-ids").val().indexOf("999") >= 0) {
            $(".form-group-types-other").show();
            $("#type-other-entry").attr('name', 'type_other_entry' + '<?php echo $specialothertype; ?>');
            $("#type-other-entry").attr('required','required');
            $("#type-other-entry").val('<?php echo $specialothervaluetype; ?>');
        } else  {
            $(".form-group-types-other").hide();           
        }
    }


    function showOtherBoxR() {
        if($("#types-ids").val().indexOf("999") >= 0) {
            $(".form-group-types-other").show(); 
            $("#type-other-entry").attr('required','required');        
        } else  {
            $(".form-group-types-other").hide();
            $("#type-other-entry").removeAttr('required');           
        }
    }    


</script>