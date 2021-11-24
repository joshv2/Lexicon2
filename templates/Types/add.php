<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Type $type
 */
?>
<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
</nav>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Types'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="types form content">
            <?= $this->Form->create($type) ?>
            <fieldset>
                <legend><?= __('Add Type') ?></legend>
                <?php
                    echo $this->Form->control('type');
                    echo $this->Form->hidden('language_id', ['value' => $sitelang->id]);
                    echo $this->Form->control('top');
                    //echo $this->Form->control('words._ids', ['options' => $words]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
