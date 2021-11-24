<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Origin $origin
 */
?>
<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
</nav>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $origin->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $origin->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Origins'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="origins form content">
            <?= $this->Form->create($origin) ?>
            <fieldset>
                <legend><?= __('Edit Origin') ?></legend>
                <?php
                    echo $this->Form->control('origin');
                    //echo $this->Form->control('words._ids', ['options' => $words]);
                    echo $this->Form->control('top');
                    echo $this->Form->hidden('language_id', ['value' => $sitelang->id]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
