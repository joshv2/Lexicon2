<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Suggestion $suggestion
 */
?>
<div class="row">
    <!--<aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Suggestions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>-->
    <div class="column-responsive column-80">
        <div class="suggestions form content">
            <?= $this->Form->create($suggestion) ?>
            <fieldset>
                <legend><?= __('Add Suggestion for ' . $word->spelling) ?></legend>
                <?php
                    echo $this->Form->hidden('word_id', ['value' => $word->id]);
                    //echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('full_name');
                    echo $this->Form->control('email');
                    echo $this->Form->hidden('status', ['value' => 'unread']);
                    echo $this->Form->control('suggestion');
                    echo "<div class='g-recaptcha' data-sitekey='" . $recaptcha_user . "'></div>";
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
