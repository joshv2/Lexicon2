<section id="main">
<h4>Choose a sentence to record</h4>
<?= $this->Form->create() ?>
<?php
$radioOptions = [];
foreach ($word->sentences as $s) {
    array_push($radioOptions, ['value' => $s['id'] , 'text' => $s['sentence']]);
} ?>
<?php echo $this->Form->radio('sentenceToRecord', $radioOptions, ['escape' => false]); ?>
</section>
<?= $this->Form->submit(__('Proceed to Recording'), ['class' => 'button blue']) ?>