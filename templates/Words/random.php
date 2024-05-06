<section id="main">
	<nav id="browse" class="group">
		<ul class="browse_nav">
			<li class="first main">
			<?=$this->Html->link('<i class="fa-solid fa-rotate-right"></i>' . __('Refresh'), '/random',
											['class' => 'main', 'escape' => false]);?>
			<div class="clear"></div>
		</ul>
	</nav>
	<div id="browse_info">
		<p class="m0"><?php echo sizeof($words);?> <?=__("random words retrieved")?></p><p id="refresh"><?=$this->Html->link('<i class="fa-solid fa-rotate-right"></i> ' . __('Refresh'), '/random',
											['class' => 'main', 'escape' => false]);?></p>
	</div>
	<ol class="word-list group">
	<?php $i = 1;
	foreach ($words as $word): ?>
		<li class="group">
			<div class="num"><?php echo $i;?></div>
			<div class="word-main">
					<h3><?php echo $this->Html->link($word->spelling, '/words//'.$word->id); ?></h3>
					<?php echo $this->Html->link(__('SEE FULL ENTRY') . ' <i class="fa fa-caret-down"></i>', '/words//'.$word->id, ['class' => 'noborder', 'escape' => false]); ?>
					
					<!--<p class="definition"><?php echo sizeof($word->definitions) > 0 ? $word->definitions[0]->definition : '';?></p>-->
				</div>
		</li>
	<?php ++$i; endforeach; ?>
	</ol>
	<div class="pagination">
		<ul class="pagination group">
			<li class="prev"><?=$this->Html->link('<i class="fa-solid fa-rotate-right"></i> ' . __('Refresh'), '/random',
											['escape' => false]);?></li>
			<li class="next"><a href="#"><i class="fa-solid fa-arrow-up"></i><?= __('Top') ?></a></li>
		</ul>
		<div class="clear"></div>
	</div>
</section>