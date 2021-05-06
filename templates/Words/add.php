<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Word $word
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Words'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="words form content">
            <?= $this->Form->create($word) ?>
            <fieldset>
                <legend><?= __('Add Word') ?></legend>
                <?php
                    echo "<div class='form-group'>";
                    echo $this->Form->control('spelling', ['label' => ['text' => 'Most Common Spelling', 'class' => 'req'], 'required' => TRUE]);
                    echo "</div>";

                    echo "<div class='form-group'>";
                    echo $this->Form->control('alternates.0.id',['class' => 'muliplespid']);
                    echo $this->Form->control('alternates.0.spelling', ['label' => 'Alternate Spelling(s)', 'class' => 'muliplespsp']);
                    echo "<a class='add'><i class='icon-plus-sign'></i> Add an additional spelling</a>&nbsp;&nbsp;";
				    echo "<a class='remove disabled'><i class='icon-minus-sign'></i> Remove</a>";
                    echo "</div>";

                    echo "<div class='form-group'>";
                    echo $this->Form->control('definitions.0.id',['class' => 'muliplespid']);
                    echo $this->Form->control('definitions.0.definition', ['label' => ['text' => 'Definition(s)', 'class' => 'req'], 'class' => 'muliplespsp']);
                    echo "<a class='add'><i class='icon-plus-sign'></i> Add an additional definition</a>&nbsp;&nbsp;";
				    echo "<a class='remove disabled'><i class='icon-minus-sign'></i> Remove</a>";
                    echo "</div>";

                    echo "<div class='form-group'>";
                    echo $this->Form->control('sentences.0.id',['class' => 'muliplespid']);
                    echo $this->Form->control('sentences.0.definition', ['label' => 'Example Sentence(s)', 'class' => 'muliplespsp']);
                    echo "<a class='add'><i class='icon-plus-sign'></i> Add an additional sentence</a>&nbsp;&nbsp;";
				    echo "<a class='remove disabled'><i class='icon-minus-sign'></i> Remove</a>";
                    echo "</div>";

                    echo "<div class='form-group left'>";
                    echo $this->Form->control('origins._ids', ['options' => $origins, 'label' => 'Language(s) of Origin', 'style' => 'width:100%;display:block;']);
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
                    echo "</div>";
                    
                    echo "<div class='form-group right'>";
                    echo $this->Form->control('regions._ids', ['options' => $regions, 'label' => 'Regions in Which the Word is Used', 'style' => 'width:100%;display:block;']);
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
                    }
                    echo "<p>Double check your submission!</p>";
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['class' => "button blue"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<!--<script>
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
                if (newData.response.spelling == "There are words") {
                    $('#wordexists').text("True");
                } else {
                    $('#wordexists').text("False");
                }
            }
        })
    });
})

</script>-->