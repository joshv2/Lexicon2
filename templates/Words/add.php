<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Word $word
 */
?>


<section id="main" class="main">
	<div class="page-header group">
		<h2 class="left">New word</h2>
	</div>
    <div class="c add">
            <?= $this->Form->create($word, ['id' => 'add_form','enctype' => 'multipart/form-data']) ?>

                <?php
                    echo "<div class='form-group'>";
                    echo $this->Form->control('spelling', ['label' => ['text' => 'Most Common Spelling', 'class' => 'req'], 'required' => TRUE]);
                    echo "</div>";
                    echo "<div id='wordexists' style='color:red'></div>";

                    echo "<div class='form-group'>";
                    echo $this->Form->control('alternates.0.id',['class' => 'muliplespid', 'data-counter' => '0']);
                    echo $this->Form->control('alternates.0.spelling', ['label' => 'Alternate Spelling(s)', 'class' => 'multiple']);
                    echo "<a class='add'><i class='icon-plus-sign'></i> Add an additional spelling</a>&nbsp;&nbsp;";
				    echo "<a class='remove disabled'><i class='icon-minus-sign'></i> Remove</a>";
                    echo "</div>";

                    /*echo $this->Form->control('submittedfile', [
                        'type' => 'file'
                    ]);*/
                    ?>
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
                    
                    echo "<div class='form-group'>";
                    echo $this->Form->control('definitions.0.id',['class' => 'muliplespid', 'data-counter' => '0']);
                    echo $this->Form->control('definitions.0.definition', ['label' => ['text' => 'Definition(s)', 'class' => 'req'], 'class' => 'muliplespsp']);
                    echo "<a class='add'><i class='icon-plus-sign'></i> Add an additional definition</a>&nbsp;&nbsp;";
				    echo "<a class='remove disabled'><i class='icon-minus-sign'></i> Remove</a>";
                    echo "</div>";

                    echo "<div class='form-group'>";
                    echo $this->Form->control('sentences.0.id',['class' => 'muliplespid', 'data-counter' => '0']);
                    echo $this->Form->control('sentences.0.sentence', ['label' => 'Example Sentence(s)', 'class' => 'muliplespsp', 'size' => '60']);
                    echo "<a class='add'><i class='icon-plus-sign'></i> Add an additional sentence</a>&nbsp;&nbsp;";
				    echo "<a class='remove disabled'><i class='icon-minus-sign'></i> Remove</a>";
                    echo "</div>";

                    echo "<div class='form-group left'>";
                    echo $this->Form->control('origins._ids', ['options' => $origins, 'label' => 'Language(s) of Origin', 'style' => 'width:100%;display:block;']);
                    echo "<p class='mini'>Hold down Ctrl to select more than one option</p>";
                    echo "</div>";

                    echo "<div class='form-group clear'>";
                    echo $this->Form->label('etymology');
                    echo "<p class='notes'>Etymology of the word</p>";
                    echo $this->Form->control('etymology', ['label' => false]);
                    echo "</div>";

                    //echo $this->Form->control('approved');
                    //echo $this->Form->control('language_id');
                    echo $this->Form->hidden('language_id', ['value' => 1]);
                    
                    //echo $this->Form->control('dictionaries._ids', ['options' => $dictionaries]);
                    
                    
                    echo "<div class='form-group left'>";
                    echo $this->Form->control('types._ids', ['options' => $types, 'label' => 'Who Uses This', 'style' => 'width:100%;display:block;']);
                    echo "<p class='mini'>Hold down Ctrl to select more than one option</p>";
                    echo "</div>";
                    
                    echo "<div class='form-group right'>";
                    echo $this->Form->control('regions._ids', ['options' => $regions, 'label' => 'Regions in Which the Word is Used', 'style' => 'width:100%;display:block;']);
                    echo "<p class='mini'>Hold down Ctrl to select more than one option</p>";
                    echo "</div>";

                    echo "<div class='form-group clear'>";
                    echo $this->Form->label('notes');
                    echo "<p class='notes'>Pronunciation, context, or anything else you want website visitors to know about this entry</p>";
                    echo $this->Form->control('notes', ['label' => false]);
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
                        echo "<div class='g-recaptcha' data-sitekey='6LdXhXwUAAAAAE6bcodYGt-FgNvlUJdcme3WprFh'></div>";
                    }
                    echo "<p>Double check your submission!</p>";
                ?>

            <?= $this->Form->button(__('Submit'), ['class' => "button blue", 'id'=> "submitbutton"]) ?>
            <?= $this->Form->end() ?>
    </div>
</section>


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