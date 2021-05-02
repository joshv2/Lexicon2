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
                    echo $this->Form->control('spelling');

                    echo $this->Form->control('alternates.0.id',['class' => 'muliplespid']);
                    echo $this->Form->control('alternates.0.spelling', ['label' => 'Alternate Spelling(s)', 'class' => 'muliplespsp']);
                    echo "<a class='add'><i class='icon-plus-sign'></i> Add an additional spelling</a>&nbsp;&nbsp;";
				    echo "<a class='remove disabled'><i class='icon-minus-sign'></i> Remove</a>";

                    echo $this->Form->control('definitions.0.id',['class' => 'muliplespid']);
                    echo $this->Form->control('definitions.0.definition', ['label' => 'Definition(s)', 'class' => 'muliplespsp']);
                    echo "<a class='add'><i class='icon-plus-sign'></i> Add an additional definition</a>&nbsp;&nbsp;";
				    echo "<a class='remove disabled'><i class='icon-minus-sign'></i> Remove</a>";

                    echo $this->Form->control('sentences.0.id',['class' => 'muliplespid']);
                    echo $this->Form->control('sentences.0.definition', ['label' => 'Example Sentences(s)', 'class' => 'muliplespsp']);
                    echo "<a class='add'><i class='icon-plus-sign'></i> Add an additional sentence</a>&nbsp;&nbsp;";
				    echo "<a class='remove disabled'><i class='icon-minus-sign'></i> Remove</a>";

                    echo $this->Form->control('etymology');
                    echo $this->Form->control('notes');
                    echo $this->Form->control('approved');
                    echo $this->Form->control('language_id');
                    echo $this->Form->control('user_id');
                    echo $this->Form->control('dictionaries._ids', ['options' => $dictionaries]);
                    echo $this->Form->control('origins._ids', ['options' => $origins]);
                    echo $this->Form->control('regions._ids', ['options' => $regions]);
                    echo $this->Form->control('types._ids', ['options' => $types]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
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