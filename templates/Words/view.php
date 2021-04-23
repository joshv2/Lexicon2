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
		
		
		<h4>Pronunciations</h4>
		<table>
		<?=  $this->Html->tableHeaders(['Spelling', 'Listen', 'Pronunciation', 'Notes']);?>
		<?php foreach ($word->pronunciations as $p): ?>
			<?php echo $this->Html->tableCells([[$p->spelling, $p->sound_file, $p->pronunciation, $p->notes]]); ?>
		<?php endforeach; ?>
		</table>


		<h4>Definitions</h4>
		<ul class="definitions">
			<?php foreach ($Definitions_definition as $d): ?><li><?php echo $d;?></li><?php endforeach; ?>
		</ul>

		<?php if (!empty($Sentences_sentence)): ?>
			<h4>Example Sentences</h4>
			<ul class="sentences">
			<?php foreach ($Sentences_sentence as $s): ?>
				<li><?php echo $s;?></li>
			<?php endforeach; ?>
		</ul><?php endif;?>

		<?php if(!empty($word->origins)):?>
			<h4>Languages of Origin</h4>
			<?php foreach ($word->origins as $s): ?>
			<li><?php echo $s->origin;?></li>
			<?php endforeach; ?>
		<?php endif;?>

		<?php if(!empty($word->etymology)):?>
			<h4>Etymology</h4>
			<p class="notes"><?php echo $word->etymology;?></p>
		<?php endif;?>
		
		<?php if(!empty($Types_type)):?>
			<h4>Who Uses This</h4>
			<?php foreach ($Types_type as $s): ?>
			<li><?php echo $s;?></li>
			<?php endforeach; ?>
		<?php endif;?>
		
		<?php if(!empty($word->regions)):?>
			<h4>Regions</h4>
			<?php foreach ($word->regions as $s): ?>
			<li><?php echo $s->region;?></li>
			<?php endforeach; ?>
		<?php endif;?>


		<?php if(!empty($word->dictionaries)):?>
			<h4>Dictionaries</h4>
			<?php foreach ($word->dictionaries as $s): ?>
			<li><?php echo $s->dictionary;?></li>
			<?php endforeach; ?>
		<?php endif;?>

		
		<?php if(!empty($word->notes)):?>
			<h4>Notes</h4>
			<p class="notes"><?php echo $word->notes;?></p>
		<?php endif;?>
		
		<?php if (!empty($word->alternates)): ?>
			<h4>Alternative Spellings</h4>
			<p><?php echo implode(', ', $Alternates_spelling); ?></p>
		<?php endif;?>

	</div>
	<div class="c wordedit">
		<p class="m0">
		<?=$this->Html->link('<i class="icon-edit"></i> Edit', '/words//'.$word->id.'/edit',
											['class' => 'button blue', 'escape' => false]);?>
		&nbsp;&nbsp;&nbsp;&nbsp;See something you disagree with? Feel free to edit it. All changes will be moderated. </p>
	</div>
</section>
