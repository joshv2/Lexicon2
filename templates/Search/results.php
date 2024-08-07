<section id="main">
	<div id="browse_info">
		<p class="m0"><?= __('Your search for'); ?> <b><?php echo h($q);?></b> <?= __('returned'); ?> <?php echo $this->Paginator->counter('{{count}}');?> <?= __('results.');?></p>
	</div>
	<?php if ($this->Paginator->counter('{{count}}') <= 0):?>

		<div class="c content">
			<p><?= __('That word is not yet in the database. Try searching with a different spelling.');?></p>
			<p><?= __("If you still don't find it, please help to make this lexicon more complete by adding it");?>:&nbsp;&nbsp;&nbsp;&nbsp;<?=$this->Html->link('<i class="fa-solid fa-plus"></i> ' . __('Add a new word'), '/add',
										['class' => 'button blue', 'escape' => false]);?></p>
		</div>

	<?php else: ?>
		<ol class="word-list group">
		<?php $i = 1;
		foreach ($words as $word): ?>
			<li class="group">
				<div class="num"><?php echo $i;?></div>
				<div class="word-main">
					<h3><?php echo $this->Html->link($word->spelling, '/words//'.$word->id); ?></h3>
					<?php echo $this->Html->link(__('SEE FULL ENTRY') . ' <i class="fa fa-caret-down"></i>', '/words//'.$word->id, ['class' => 'noborder', 'escape' => false]); ?>
					
				</div>
			</li>
		<?php ++$i; endforeach; ?>
		</ol>
		<div class="pagination">
			<?php if ($this->Paginator->hasPrev()) :?>
					<?= $this->Paginator->prev(' << ' . __('previous'));?>
				<?php endif ?>
				<?php if ($this->Paginator->hasNext()) :?>
					<?= $this->Paginator->next(' >> ' . __('next'));?>
				<?php endif ?>	
			<div class="clear"></div>
		</div>

	<?php endif;?>
</section>
