<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
	<ul class="right">
		<li>
		<?=$this->Html->link('&larr; Back', '/words',
										['class' => 'button grey', 'escape' => false]);?></li>
	</ul>
</nav>
<section id="main">
	<div class="page-header group">
		<h2 class="left"><?php echo $word->spelling;?></h2>
		<ul class="editbuttons">
			<?php if ($this->Identity->isLoggedIn()):?>
				<li><?php
				echo $this->Form->postLink('<i class="icon-trash"></i> Delete', 
											'/moderators/words/delete/'.$word->id, 
											['escape' => false, 'class' => 'button blue', 'confirm' => 'Are you sure you want to delete '.$word->spelling.'?']);
				?></li>
				<li>
				<?=$this->Html->link('Admin Edit', '/moderators/words/'.$word->id.'/edit',
											['class' => 'button blue']);?>
				</li>
			<?php endif;?>
			<li>
			<?=$this->Html->link('<i class="icon-edit"></i> Edit', '/words//'.$word->id.'/edit',
											['class' => 'button blue', 'escape' => false]);?>
			</li>
		</ul>
	</div>
	<div class="page-header group2">
	<?=$this->Html->link('Edit', '/words//'.$word->id.'/edit');?>
	</div>
	<div class="c word">
		<h3>
			<?php echo $word->spelling; ?>
		</h3>

		<?php if (!empty($alternates)): ?><h4>Alternative Spellings</h4>
		<p><?php echo $alternates; ?></p><?php endif;?>

		<h4>Definitions</h4>
		<ul class="definitions">
			<?php foreach ($word['Definition'] as $d): ?><li><?php echo $d['definition'];?></li><?php endforeach; ?>
		</ul>

		<?php if (!empty($word['Sentence'])): ?><h4>Example Sentences</h4>
		<ul class="sentences">
		<?php foreach ($word['Sentence'] as $s): ?>
			<li><?php echo $s['sentence'];?></li>
		<?php endforeach; ?>
		</ul><?php endif;?>

		<?php if(!empty($origins)):?><h4>Languages of Origin</h4>
		<p class="origins"><?php echo $origins;?></p><?php endif;?>

		<?php if(!empty($word['Word']['etymology'])):?><h4>Etymology</h4>
		<p class="notes"><?php echo $word['Word']['etymology'];?></p><?php endif;?>
		
		<?php if(!empty($uses)):?><h4>Who Uses This</h4>
		<p class="uses"><?php echo $uses;?></p><?php endif;?>
		
		<?php if(!empty($regions)):?><h4>Regions</h4>
		<p class="regions"><?php echo $regions;?></p><?php endif;?>

		<h4>Dictionaries</h4>
		<p class="dictionaries"><?php echo (!empty($dictionaries))? $dictionaries : 'None';?></p>

		<?php if(!empty($word['Word']['notes'])):?><h4>Notes</h4>
		<p class="notes"><?php echo $word['Word']['notes'];?></p><?php endif;?>

	</div>
	<div class="c wordedit">
		<p class="m0"><a class="button blue" href="<?php
			echo $this->Html->url('/words/'.$word['Word']['id'].'/edit/', true);
			?>"><i class="icon-edit"></i> Edit</a> 
			&nbsp;&nbsp;&nbsp;&nbsp;See something you disagree with? Feel free to edit it. All changes will be moderated. </p>
	</div>
</section>
