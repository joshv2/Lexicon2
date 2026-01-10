<section id="main">
<div id="header-image">
	<?php if ('' != $sitelang_all->HeaderImage):?>
		<?php echo $this->Html->image($sitelang_all->i18nspec . '/' . $sitelang_all->HeaderImage, ['height' => 200, 'width' => 900])?>
	<?php endif; ?>
</div>
<div class="page-header group">
	<?php if ('' !== $sitelang_all->NotesSec1Header): ?>	
		<h2 class="left"><?= $sitelang_all->NotesSec1Header ?></h2>
	<?php else: ?>
		<h2 class="left"><?= __('Notes'); ?></h2>
	<?php endif; ?>
</div>  
<div id="notes" class="c content">
	<?php if ('' !== $sitelang_all->NotesSec1Text): ?>
		<?= $sitelang_all->NotesSec1Text ?>
	<?php else: ?>
		<p><?= __("")?></p>
		
	<?php endif; ?>
	<!--</div>-->
</div>
<a id="bottom"></a>

</section>
