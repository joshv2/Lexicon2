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
				<li>
				<?php echo 
			$this->AuthLink->postLink(
                '<i class="icon-trash"></i> Delete',
                ['prefix' => false, 'controller' => 'Words', 'action' => 'delete', $word['id']],
                ['escape' => false, 'class' => 'button red', 'confirm' => 'Are you sure you want to delete '.$word->spelling.'?']);?>
				</li>
				<li>
				<?=$this->Html->link('<i class="fas fa-microphone"></i> Record a Pronunciation', '/pronunciations/add/' .$word->id,
											['class' => 'button blue', 'escape' => false]);?>
				</li>
			<?php endif;?>
			<li>
			<?=$this->Html->link('<i class="icon-edit"></i> Edit', '/words/edit/' .$word->id,
											['class' => 'button blue', 'escape' => false]);?>
			</li>
		</ul>
	</div>
	<div class="page-header group2">
	<?=$this->Html->link('Edit', '/words/edit/' .$word->id);?>
	</div>
	<div class="c word">
		<h3>
			<?php echo $word->spelling; ?>

		</h3>
		
		<?php if(!empty($word->pronunciations)) : ?>
			<!--<h4>Pronunciations</h4>
			<table>-->
			<!--<?=  $this->Html->tableHeaders(['Spelling', 'Listen', 'Pronunciation']);?>-->
			<?php $i = 0; ?>
			<?php foreach ($word->pronunciations as $p): ?>
				<?php if(1 == $p->approved): ?>
					<?php if (0 === $i){
						 echo '<h4>Pronunciations</h4><table>';
					} ?>
				<?php 
					if ('' !== $p->sound_file){
						$audioPlayer = '<a id="play-pause-button-' . $i . '" class="fa fa-play"></a>' . $this->Html->media($p->sound_file, ['pathPrefix' => 'recordings/', 'controls', 'class' => 'audioplayers', 'id' => 'audioplayer'. $i]);
					} else {
						$audioPlayer = '';
					}
					 ?>
				<?php echo $this->Html->tableCells([[$p->spelling, "(" . $p->pronunciation . ")", $audioPlayer]]); ?>
				<?php endif; ?>
				<?php $i += 1; ?>
			<?php endforeach; ?>
			</table>
		<?php endif; ?>
		<?php if (!empty($Definitions_definition)): ?>
		<h4>Definitions</h4>
		<ul class="multiple-items">
			<?php foreach ($Definitions_definition as $d): ?>
        <li><?php echo $d;?></li>
      <?php endforeach; ?>
      <?php if (count($Definitions_definition) > 3): ?>
        <li class="view-more-link"><a href="#">View More</a></li>
      <?php endif; ?>
		</ul><?php endif;?>

		<?php if (!empty($Sentences_sentence)): ?>
			<h4>Example Sentences</h4>
			<ul class="sentences multiple-items">
			<?php foreach ($Sentences_sentence as $s): ?>
				<li><?php echo $s;?></li>
			<?php endforeach; ?>
			<?php if (count($Sentences_sentence) > 3): ?>
				<li class="view-more-link"><a href="#">View More</a></li>
			<?php endif; ?>
		</ul><?php endif;?>

		<?php if(!empty($word->origins)):?>
			<h4>Languages of Origin</h4>
			<ul class="multiple-items">
			<?php foreach ($word->origins as $s): ?>
			<li><?php echo $s->origin;?></li>
			<?php endforeach; ?>
			<?php if (count($word->origins) > 3): ?>
				<li class="view-more-link"><a href="#">View More</a></li>
			<?php endif; ?>
		</ul><?php endif;?>

		<?php if(!empty($word->etymology)):?>
			<h4>Etymology</h4>
			<ul class='sentences'>
			<li><?php echo $word->etymology;?></li>
			<ul>
		<?php endif;?>
		
		<?php if(!empty($Types_type)):?>
			<h4>Who Uses This</h4>
			<ul class="multiple-items">
			<?php foreach ($Types_type as $s): ?>
			<li><?php echo $s;?></li>
			<?php endforeach; ?>
			<?php if (count($Types_type) > 3): ?>
				<li class="view-more-link"><a href="#">View More</a></li>
			<?php endif; ?>
		</ul><?php endif;?>
		
		<?php if(!empty($word->regions)):?>
			<h4>Regions</h4>
			<ul class="multiple-items">
			<?php foreach ($word->regions as $s): ?>
			<li><?php echo $s->region;?></li>
			<?php endforeach; ?>
			<?php if (count($word->regions) > 3): ?>
				<li class="view-more-link"><a href="#">View More</a></li>
			<?php endif; ?>
		</ul><?php endif;?>


		<?php if(!empty($word->dictionaries)):?>
			<h4>Dictionaries</h4>
			<ul class="multiple-items">
			<?php foreach ($word->dictionaries as $s): ?>
			<li><?php echo $s->dictionary;?></li>
			<?php endforeach; ?>
			<?php if (count($word->dictionaries) > 3): ?>
				<li class="view-more-link"><a href="#">View More</a></li>
			<?php endif; ?>
		</ul><?php endif;?>

		
		
		
		<?php if (!empty($word->alternates)): ?>
			<h4>Alternative Spellings</h4>
			<p><?php echo implode(', ', $Alternates_spelling); ?></p>
		<?php endif;?>
		<?php if(!empty($word->notes)):?>
			<h4>Notes</h4>
			<ul>
				<li><?php echo $word->notes;?></li>
			</ul>
		<?php endif;?>
	</div>
	<div class="c wordedit">
		<p class="m0">
		<?=$this->Html->link('<i class="icon-edit"></i> Edit', '/words/edit/' .$word->id,
											['class' => 'button blue', 'escape' => false]);?>
		&nbsp;&nbsp;&nbsp;&nbsp;See something you disagree with? Feel free to edit it. All changes will be moderated. </p>
	</div>
</section>
