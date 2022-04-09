<section id="main">
	<div class="page-header2 group">
		<h2 class="left"><?php echo $word->spelling;?></h2>
		<ul class="editbuttons">
			<?php if ($this->Identity->isLoggedIn()):?>
				<li>
				<?php echo 
			$this->AuthLink->postlink(__(
                '<i class="icon-trash"></i> Delete'),
                ['prefix' => false, 'controller' => 'Words', 'action' => 'delete', $word['id']],
                ['escape' => false, 'class' => 'button red', 'confirm' => 'Are you sure you want to delete '.$word->spelling.'?']);?>
				</li>
				<?php endif;?>
				<li>
				<?php 
				if($this->Identity->isLoggedIn() && count($word->pronunciations) == 0) {echo $this->Html->link(__('<i class="fas fa-microphone"></i> Record a Pronunciation'), '/pronunciations/add/' .$word->id,
											['class' => 'nomargin button blue', 'escape' => false]);}
				elseif (count($word->pronunciations) == 0) {echo $this->Html->link(__('<i class="fas fa-microphone"></i> Record a Pronunciation'), '/login?redirect=/pronunciations/add/' .$word->id,
					['class' => 'nomargin button blue', 'escape' => false]);}
											?>
				</li>
			
			<li>
			<?=$this->Html->link(__('<i class="icon-edit"></i> Edit'), '/words/edit/' .$word->id,
											['class' => 'button blue nl', 'escape' => false]);?>
			</li>
		</ul>
	</div>
	<div class="page-header group2">
	<?=$this->Html->link(__('Edit'), '/words/edit/' .$word->id);?>
	</div>
	<div class='c'>
	<div class="word">
		<!--<h3>
			<?php echo $word->spelling; ?>

		</h3>-->
		
		<?php if(!empty($word->pronunciations)) : ?>
			<h4>Pronunciations</h4>
			<div class='section-container'>
			<div class='table-container'>
			<table>
			<!--<?=  $this->Html->tableHeaders(['Spelling', 'Listen', 'Pronunciation']);?>-->
			<?php $i = 0; ?>
			<?php foreach ($word->pronunciations as $p): ?>
				<?php if(1 == $p->approved): ?>
					
				<?php 
					if ('' !== $p->sound_file){
						$audioPlayer = '<a id="play-pause-button-' . $i . '" class="fa fa-volume-up"> <span id="listen">listen</span></a>' . $this->Html->media(str_replace('.webm', '.mp3', $p->sound_file), ['pathPrefix' => 'recordings/', 'controls', 'class' => 'audioplayers', 'id' => 'audioplayer'. $i]);
					} else {
						$audioPlayer = '';
					}
					 ?>
				<?php echo $this->Html->tableCells([[$p->spelling, "(" . $p->pronunciation . ")", $audioPlayer]], ['class' => 'pronunciationtr']); ?>
				<?php endif; ?>
				<?php $i += 1; ?>
			<?php endforeach; ?>
			</table>
				</div>
			<div class='delete3'><div class='vertical-center'>
				<p><?php 
				if ($this->Identity->isLoggedIn() && count($word->pronunciations) > 0) {
						echo $this->Html->link(__('<i class="fas fa-microphone"></i> Record a Pronunciation'), '/pronunciations/add/' .$word->id,
						['class' => 'button blue', 'escape' => false]);
					
						} elseif  (count($word->pronunciations) > 0){
							echo $this->Html->link(__('<i class="fas fa-microphone"></i> Record a Pronunciation'), '/login?redirect=/pronunciations/add/'.$word->id,
													['class' => 'button blue ', 'escape' => false]);
						}?></p>
			</div></div>
			</div>
		<?php endif; ?>
		<?php if (!empty($Definitions_definition)): ?>
		<h4>Definitions</h4>
		<ul class="multiple-items">
			<?php foreach ($Definitions_definition as $d): ?>
        <li><?php echo $d;?></li>
      <?php endforeach; ?>
      <?php if (count($Definitions_definition) > 3): ?>
        <li class="view-more-link"><a href="#"><?=__("View More")?></a></li>
      <?php endif; ?>
		</ul><?php endif;?>

		<?php if (!empty($word['sentences'])): ?>
			<h4>Example Sentences</h4>
			<div class='section-container'>
				<div class='table-container'>
				<ul class="sentences multiple-items">
				<?php $j = count($word->pronunciations) + 1;
					?>
				<?php foreach ($word['sentences'] as $s): ?>
					<?php $k = 1; ?>
					<li class="pronunciationtr2"><?php echo $s['sentence'];
					if (count($s['sentence_recordings']) > 0) {
						echo "<span class='recordinglist'>Listen to recordings of this sentence: (";
						
						$linkArray = [];
						foreach ($s['sentence_recordings'] as $r) {
							if($r['approved'] == 1){
							array_push($linkArray, '<a id="play-pause-button-' . $j . '" class="fa fa-volume-up"> <span id="listen">Recording ' . $k  . '</span></a>' . $this->Html->media(str_replace('.webm', '.mp3', $r['sound_file']), ['pathPrefix' => 'recordings/', 'controls', 'class' => 'audioplayers', 'id' => 'audioplayer'. $j]));
							$j += 1;
							$k += 1;
						}}
					echo implode($linkArray) . ")</span>";
				}
					
					?></li>
				<?php endforeach; ?>
				<?php if (count($word['sentences']) > 3): ?>
					<li class="view-more-link"><a href="#"><?=__("View More")?></a></li>
				<?php endif; ?>
				</ul>
				</div>
		
			<div class='delete4'><div class='vertical-center'>
				<p><?php 
				if ($this->Identity->isLoggedIn()) {
					if (count($word['sentences']) == 1){
					echo $this->Html->link(__('<i class="fas fa-microphone"></i> Record a Sentence'), '/SentenceRecordings/add/' .$word->sentences[0]->id,
											['class' => 'button blue', 'escape' => false]);
					} else {
						echo $this->Html->link(__('<i class="fas fa-microphone"></i> Record a Sentence'), '/SentenceRecordings/choose/' .$word->id,
											['class' => 'button blue', 'id' => 'convert', 'escape' => false]);
					}
				} else {
					if (count($word['sentences']) == 1){
						echo $this->Html->link(__('<i class="fas fa-microphone"></i> Record a Sentence'), '/login?redirect=/SentenceRecordings/add/' .$word->sentences[0]->id,
											['class' => 'button blue', 'escape' => false]);
					} else {
					echo $this->Html->link(__('<i class="fas fa-microphone"></i> Record a Sentence'), '/login?redirect=/SentenceRecordings/choose/' . $word->id,
											['class' => 'button blue', 'escape' => false]);
					}
				}?></p>
			</div></div>
		</div>
		<?php endif;?>
		<?php if(!empty($word->origins)):?>
			<h4>Languages of Origin</h4>
			<ul class="multiple-items">
			<?php foreach ($word->origins as $s): ?>
			<li><?php echo $s->origin;?></li>
			<?php endforeach; ?>
			<?php if (count($word->origins) > 3): ?>
				<li class="view-more-link"><a href="#"><?=__("View More")?></a></li>
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
				<li class="view-more-link"><a href="#"><?=__("View More")?></a></li>
			<?php endif; ?>
		</ul><?php endif;?>
		
		<?php if(!empty($word->regions)):?>
			<h4>Regions</h4>
			<ul class="multiple-items">
			<?php foreach ($word->regions as $s): ?>
			<li><?php echo $s->region;?></li>
			<?php endforeach; ?>
			<?php if (count($word->regions) > 3): ?>
				<li class="view-more-link"><a href="#"><?=__("View More")?></a></li>
			<?php endif; ?>
		</ul><?php endif;?>
			

		<?php if(!empty($word->dictionaries)):?>
			<h4>Dictionaries</h4>
			<ul class="multiple-items">
			<?php foreach ($word->dictionaries as $s): ?>
			<li><?php echo $s->dictionary;?></li>
			<?php endforeach; ?>
			<?php if (count($word->dictionaries) > 3): ?>
				<li class="view-more-link"><a href="#"><?=__("View More")?></a></li>
			<?php endif; ?>
			</ul>
		<?php else: ?>
			<h4><?=__("Dictionaries")?></h4>
			<ul>
			<li><?=__("None")?></li>
			</ul>
		<?php endif;?>
		<?php if (!empty($word->alternates)): ?>
			<?php if (strlen(implode(', ', $Alternates_spelling)) > 360):?>
				<h4><?=__("Alternative Spellings")?></h4>	
					<p><span class="more" id='addsp'><?php echo implode(', ', $Alternates_spelling); ?></span></p>
					
				
			<?php else: ?>
			<h4><?=__("Alternative Spellings")?></h4>
			<p><?php echo implode(', ', $Alternates_spelling); ?></p>
			<?php endif; ?>
		<?php endif;?>
		</div>
		<div class='wnotes'>
		<?php if(!empty($word->notes)):?>
			<h4><?=__("Notes")?></h4>
			<ul>
				<li class='notesli'><?php echo $word->notes;?></li>
			</ul>
		<?php endif;?>
	</div>
		</div>

	<div class="c wordedit">
		<p class="m0">
		<?=$this->Html->link('<i class="icon-edit"></i>' . __(' Edit'), '/words/edit/' .$word->id,
											['class' => 'button blue', 'escape' => false]);?>
		&nbsp;&nbsp;&nbsp;&nbsp;<?=__("Something missing from this entry? Inaccurate? Feel free to suggest an edit.")?></p>
	</div>
</section>

<script>
$(function(){