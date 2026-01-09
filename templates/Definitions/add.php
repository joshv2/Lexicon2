<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Definition $definition
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Definitions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="definitions form content">
            <?= $this->Form->create($definition) ?>
            <fieldset>
                <legend><?= __('Add Definition') ?></legend>
                <?php
                    if (isset($wordId)) {
                        echo $this->Form->control('word_id', ['type' => 'hidden', 'value' => $wordId]);
                        echo "<p>Adding definition for word ID: " . h($wordId) . "</p>";
                    } else {
                        echo $this->Form->control('word_id', ['options' => $words]);
                    }
                    echo $this->Form->control('definition');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
