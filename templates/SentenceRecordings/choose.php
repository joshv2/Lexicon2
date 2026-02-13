<section id="main">
<h4>Choose a sentence to record:</h4>
<?= $this->Form->create() ?>
<?php
$radioOptions = [];

$sentences = $word['sentences'] ?? [];
foreach ($sentences as $s) {
    $radioOptions[] = ['value' => $s['id'] ?? null, 'text' => $s['sentence'] ?? ''];
}

if (empty($radioOptions)) {
    echo '<p>' . __('No sentences are available to record for this word yet.') . '</p>';
} else {
    echo $this->Form->radio('sentenceToRecord', $radioOptions, ['escape' => false]);
}
?>
</section>
<?= $this->Form->submit(__('Proceed to Recording'), ['class' => 'button blue']) ?>