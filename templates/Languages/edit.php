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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $language->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $language->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Languages'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="languages form content">
            <?= $this->Form->create($language, array('id' => 'pageeditorform')) ?>
            <fieldset>
                <legend><?= __('Edit Language') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('subdomain');
                    echo $this->Form->control('HeaderImage');
                    echo $this->Form->control('AboutSec1Header', array('type' => 'text'));
                    echo "<label>Text for About Section 1</label>";
                    echo "<input type='hidden' name='AboutSec1Text'/>";
                    echo "<div id='langeditor-AboutSec1Text'></div>";
                    //echo $this->Form->control('AboutSec1Text');
                    echo $this->Form->control('AboutSec2Header', array('type' => 'text'));

                    echo "<label>Text for About Section 2</label>";
                    echo "<input type='hidden' name='AboutSec2Text'/>";
                    echo "<div id='langeditor-AboutSec2Text'></div>";

                    //echo $this->Form->control('AboutSec2Text');
                    echo $this->Form->control('AboutSec3Header', array('type' => 'text'));
                    
                    echo "<label>Text for About Section 3</label>";
                    echo "<input type='hidden' name='AboutSec3Text'/>";
                    echo "<div id='langeditor-AboutSec3Text'></div>";

                    //echo $this->Form->control('AboutSec3Text');
                    echo $this->Form->control('AboutSec4Header', array('type' => 'text'));
                    
                    echo "<label>Text for About Section 4</label>";
                    echo "<input type='hidden' name='AboutSec4Text'/>";
                    echo "<div id='langeditor-AboutSec4Text'></div>";
                    
                    //echo $this->Form->control('AboutSec4Text');
                    echo $this->Form->control('NotesSec1Header', array('type' => 'text'));
                    
                    echo "<label>Text for Notes Section 1</label>";
                    echo "<input type='hidden' name='NotesSec1Text'/>";
                    echo "<div id='langeditor-NotesSec1Text'></div>";
                    
                    //echo $this->Form->control('NotesSec1Text');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<script>
  var quill = new Quill('#langeditor-AboutSec1Text', {
    theme: 'snow'
  });

  var quill2 = new Quill('#langeditor-AboutSec2Text', {
    theme: 'snow'
  });

  var quill3 = new Quill('#langeditor-AboutSec3Text', {
    theme: 'snow'
  });

  var quill4 = new Quill('#langeditor-AboutSec4Text', {
    theme: 'snow'
  });

  var quill5 = new Quill('#langeditor-NotesSec1Text', {
    theme: 'snow'
  });

  var form = document.getElementById("pageeditorform"); // get form by ID

    form.onsubmit = function() { // onsubmit do this first
        var name = document.querySelector('input[name=AboutSec1Text]'); // set name input var
        name.value = JSON.stringify(quill.getContents()); // populate name input with quill data
        
        var name2 = document.querySelector('input[name=AboutSec2Text]'); // set name input var
        name2.value = JSON.stringify(quill2.getContents()); // populate name input with quill data
       
        var name3 = document.querySelector('input[name=AboutSec3Text]'); // set name input var
        name3.value = JSON.stringify(quill3.getContents()); // populate name input with quill data
        
        var name4 = document.querySelector('input[name=AboutSec4Text]'); // set name input var
        name4.value = JSON.stringify(quill4.getContents()); // populate name input with quill data

        var name5 = document.querySelector('input[name=NotesSec1Text]'); // set name input var
        name5.value = JSON.stringify(quill5.getContents()); // populate name input with quill data
        return true; // submit form
    }
    
    var content = JSON.parse(<?= json_encode($language->AboutSec1Text_json); ?>);
    quill.setContents(content.ops, 'api');
    var content2 = JSON.parse(<?= json_encode($language->AboutSec2Text_json); ?>);
    quill2.setContents(content2);
    var content3 = JSON.parse(<?= json_encode($language->AboutSec3Text_json); ?>);
    quill3.setContents(content3);
    var content4 = JSON.parse(<?= json_encode($language->AboutSec4Text_json); ?>);
    quill4.setContents(content4);
    var content5 = JSON.parse(<?= json_encode($language->NotesSec1Text_json); ?>);
    //var content5 = JSON.parse('<?= str_replace(':', "\u003A", str_replace(",", "\u002C", str_replace("!", "\u0021", str_replace(";", "\u003B", str_replace(array("\n", "\r", "\r\n"), "\\n", str_replace('"', '\"', str_replace("\\", "\\\\", $language->NotesSec1Text_json))))))); ?>');
    quill5.setContents(content5);
</script>

