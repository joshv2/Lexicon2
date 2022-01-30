<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
</nav>
<section id="main">
<div id="header-image">
	<?php echo $this->Html->image($sitelang->HeaderImage, ['height' => 200, 'width' => 900])?>
</div>
<div class="page-header group">
	<?php if ('' !== $sitelang->AboutSec1Header): ?>	
		<h2 class="left"><?= $sitelang->AboutSec1Header ?></h2>
	<?php else: ?>
		<h2 class="left"><?= __('Welcome'); ?></h2>
	<?php endif; ?>
</div>  
<div id="notes" class="c content">
	<?php if ('' !== $sitelang->AboutSec1Text): ?>
		<?= $sitelang->AboutSec1Text ?>
		<p id="enterthelexicon">
		<?php echo $this->Html->link(__('Enter the Lexicon'), '/', ['class' => 'button blue']);?>
		</p>
	<?php else: ?>
		<p><?= __("Coming Soon.")?></p>
		<p id="enterthelexicon">
		<?php echo $this->Html->link(__('Enter the Lexicon'), '/', ['class' => 'button blue']);?>
		</p>
	<?php endif; ?>
	<!--</div>-->
</div>
<?php if ('' !== $sitelang->AboutSec2Header): ?>	
	<div class="page-header group">
			<h2 class="left"><?= $sitelang->AboutSec2Header ?></h2>
	</div>
<?php else: ?>
	<div class="page-header group">
		<h2 class="left"><?= __('About Us'); ?></h2>
	</div>
<?php endif; ?>

<div id="notes" class="c content about">
	<p id="logo">
	<?php echo $this->Html->image('JELlogo2021.png', 
										['width' => 200,
										'height' => 204,
										'align' => 'left',
										'style' => 'margin-right:10px'])?>
	</p>
	<?php if ('' !== $sitelang->AboutSec2Text): ?>
		<?= $sitelang->AboutSec2Text ?>
	<?php endif; ?>
</div>
		
<?php if ('' !== $sitelang->AboutSec3Header): ?>	
	<div class="page-header group">
		<h2 class="left"><?= $sitelang->AboutSec3Header ?></h2>
	</div>
<?php endif;?>
<?php if ('' !== $sitelang->AboutSec3Text): ?>	
	<div id="about2" class="c content">
		<p><?= $sitelang->AboutSec3Text ?></br>
	</div>
<?php endif;?>

<?php if ('' !== $sitelang->AboutSec4Header): ?>	
	<div class="page-header group">
		<h2 class="left"><?= $sitelang->AboutSec4Header ?></h2>
	</div>
<?php endif;?>
<?php if ('' !== $sitelang->AboutSec4Text): ?>	
	<div id="about2" class="c content">
		<p><?= $sitelang->AboutSec4Text ?></br>
	</div>
<?php endif;?>

</section>
