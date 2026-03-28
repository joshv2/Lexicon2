<section id="main">
<h4>Choose a sentence to record:</h4>
<?php if (empty($word) || empty($word->sentences)) : ?>
    <p><?= __("No sentences are available for recording for this word.") ?></p>
<?php else : ?>
    <?= $this->Form->create() ?>
    <?php
    $radioOptions = [];
    foreach ((array)$word->sentences as $s) {
        if (!is_array($s) || !isset($s['id'], $s['sentence'])) {
            continue;
        }
        $radioOptions[] = ['value' => $s['id'], 'text' => $s['sentence']];
    }
    ?>
    <?= $this->Form->radio('sentenceToRecord', $radioOptions, ['escape' => false]); ?>
<?php endif; ?>
</section>
<?php if (!empty($word) && !empty($word->sentences)) : ?>
    <?= $this->Form->submit(__('Proceed to Recording'), ['class' => 'button blue']) ?>
<?php endif; ?>