<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
</nav>
<section id="main">
<div id="header-image">
	<?php echo $this->Html->image($sitelang->HeaderImage, ['height' => 200, 'width' => 900])?>
</div>
<div class="page-header group">
	<?php if ('' !== $sitelang->NotesSec1Header): ?>	
		<h2 class="left"><?= $sitelang->AboutSec1Header ?></h2>
	<?php else: ?>
		<h2 class="left"><?= __('Notes'); ?></h2>
	<?php endif; ?>
</div>  
<div id="notes" class="c content">
	<?php if ('' !== $sitelang->NotesSec1Text): ?>
		<?= $sitelang->NotesSec1Text ?>
	<?php else: ?>
		<p><?= __("")?></p>
		
	<?php endif; ?>
	<!--</div>-->
</div>


</section>
