<section id="main">
	<div class="page-header2 group">
		<h2 class="left"><?php echo $word->spelling;?></h2>
		<ul class="editbuttons">
			<?php if ($this->Identity->isLoggedIn()):?>
				<li>
				<?php echo 
			$this->AuthLink->postlink(__(
                '<i class="fa-solid fa-trash"></i> Delete'),
                ['prefix' => false, 'controller' => 'Words', 'action' => 'delete', $word['id']],
                ['escape' => false, 'class' => 'button red', 'confirm' => 'Are you sure you want to delete '.$word->spelling.'?']);?>
				</li>
				<?php endif;?>
				<li>
				<?php 
				if($this->Identity->isLoggedIn() && count($word->pronunciations) == 0) {echo $this->Html->link('<i class="fas fa-microphone"></i>' . __(' Record a Pronunciation'), '/pronunciations/add/' .$word->id,
											['class' => 'nomargin button blue', 'escape' => false]);}
				elseif (count($word->pronunciations) == 0) {echo $this->Html->link('<i class="fas fa-microphone"></i>' . __(' Record a Pronunciation'), '/login?redirect=/pronunciations/add/' .$word->id,
					['class' => 'nomargin button blue', 'escape' => false]);}
											?>
				</li>
			
			<li>
			<?=$this->Html->link('<i class="fa-solid fa-pen-to-square"></i>' . __(' Edit'), '/words/edit/' .$word->id,
											['class' => 'button blue nl', 'escape' => false]);?>
			</li>
		</ul>
	</div>
	<div class="page-header group2">
	<?=$this->Html->link(__('Edit'), '/words/edit/' .$word->id);?>
				</br>
	<?php 
	if($this->Identity->isLoggedIn() && count($word->pronunciations) == 0) {echo $this->Html->link(__('Record a Pronunciation'), '/pronunciations/add/' .$word->id);}
	elseif (count($word->pronunciations) == 0) {echo $this->Html->link(__('Record a Pronunciation'), '/login?redirect=/pronunciations/add/' .$word->id);}
											?>
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
					if (is_null($p->sound_file) || '' == $p->sound_file) {
						$audioPlayer = '';
					}
					else {
						$audioPlayer = '<a id="play-pause-button-' . $i . '"><i class="fa-solid fa-volume-up"></i> <span id="listen">listen</span></a>' . $this->Html->media(str_replace('.webm', '.mp3', $p->sound_file), ['pathPrefix' => 'recordings/', 'controls', 'class' => 'audioplayers', 'id' => 'audioplayer'. $i]);
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
				echo $this->Html->link('<i class="fas fa-microphone"></i> ' . __('Record a Pronunciation'), '/pronunciations/add/' .$word->id,
				['class' => 'button blue', 'escape' => false]);?></p>
			</div></div>
			</div>
		<?php endif; ?>
		<?php if (!empty($Definitions_definition)): ?>
		<h4><?=__("Definitions")?></h4>
		<ul class="multiple-items">
			<?php foreach ($Definitions_definition as $d): ?>
        <li><?php echo $d;?></li>
      <?php endforeach; ?>
      <?php if (count($Definitions_definition) > 3): ?>
        <li class="view-more-link"><a href="#"><?=__("View More")?></a></li>
      <?php endif; ?>
		</ul><?php endif;?>

		<?php if (!empty($word['sentences'])): ?>
			<h4><?=__("Example Sentences")?></h4>
			<div class='section-container'>
				<div class='table-container'>
				<ul class="sentences multiple-items">
				<?php $j = count($word->pronunciations) + 1;
					?>
				<?php foreach ($word['sentences'] as $s): ?>
					<?php $k = 1; ?>
					<li class="pronunciationtr2"><?php echo $s['sentence'];
					if (count($s['sentence_recordings']) > 0) {
						echo "<span class='recordinglist'>" . __('Listen to recordings of this sentence') . ": (";
						
						$linkArray = [];
						foreach ($s['sentence_recordings'] as $r) {
							if($r['approved'] == 1){
							array_push($linkArray, '<a id="play-pause-button-' . $j . '"><i class="fa-solid fa-volume-up"></i> <span id="listen">' . __('Recording') . ' ' . $k  . '</span></a>' . $this->Html->media(str_replace('.webm', '.mp3', $r['sound_file']), ['pathPrefix' => 'recordings/', 'controls', 'class' => 'audioplayers', 'id' => 'audioplayer'. $j]));
							$j += 1;
							$k += 1;
						}}
					echo implode($linkArray) . ")</span>";
				}
					
					?> 
				<?php if ($this->Identity->isLoggedIn()):?>
					<?php echo $this->AuthLink->link(__(' Manage Recordings'), ['prefix' => false, 'controller' => 'SentenceRecordings', 'action' => 'manage', $word['id'], $s['id']] ); ?>
				<?php endif; ?>	
				</li>
				<?php endforeach; ?>
				<?php if (count($word['sentences']) > 3): ?>
					<li class="view-more-link"><a href="#"><?=__("View More")?></a></li>
				<?php endif; ?>
				</ul>
				</div>
		
			<div class='delete4'><div class='vertical-center'>
				<p><?php 

					if (count($word['sentences']) == 1){
					echo $this->Html->link('<i class="fas fa-microphone"></i> ' . __('Record a Sentence'), '/SentenceRecordings/add/' .$word->sentences[0]->id,
											['class' => 'button blue', 'escape' => false]);
					} else {
						echo $this->Html->link('<i class="fas fa-microphone"></i> ' . __('Record a Sentence'), '/SentenceRecordings/choose/' .$word->id,
											['class' => 'button blue', 'id' => 'convert', 'escape' => false]);
					}
			 ?></p>
			</div></div>
		</div>
		<?php endif;?>
		<?php if(!empty($word->origins)):?>
			<?php $neworigins = [];
			foreach ($word->origins as $key => $origin){
				$lenotherorigins = 0;
				//echo $origin->origin, strpos($origin->origin,",") . "</br>";

				if(strpos($origin->origin,",") !== false && $origin->id > 999){
					//echo "comma";
					$otherorigins = explode(",",$origin->origin);
					$lenotherorigins = count($otherorigins);
				} 
				//print_r($otherorigins);


				if($origin->id != 999 && $lenotherorigins == 0){
					$neworigins[$key] = __($origin->origin);
				}

				

				$totalorigins = count($neworigins) + $lenotherorigins;
			} 
			?>
			<h4><?=__("Languages of Origin")?></h4>
			<ul class="multiple-items">
			<?php foreach ($neworigins as $s): ?>
			<li><?php echo $s;?></li>
			<?php endforeach; ?>
			<?php if (isset($otherorigins)) : ?>
				<?php foreach ($otherorigins as $s): ?>
					<li><?php echo trim($s);?></li>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ($totalorigins > 3): ?>
				<li class="view-more-link"><a href="#"><?=__("View More")?></a></li>
			<?php endif; ?>
		</ul><?php endif;?>

		<?php if(!empty($word->etymology)):?>
			<h4><?=__("Etymology")?></h4>
			<ul class='sentences'>
			<li><?php echo $word->etymology;?></li>
			<ul>
		<?php endif;?>
		
		<?php if(!empty($word->types)):?>
			<?php $newtypes = [];
			foreach ($word->types as $key => $type){
				$lenothertypes = 0;

				/*if(strpos($type->type,",") !== false && $type->id > 999){ // TODO fix this logic it is bad!!
					$othertypes = explode(",",$type->type);
					$lenothertypes = count($othertypes);
				}*/

				if($type->id != 999 && $lenothertypes == 0){
					$newtypes[$key] = __($type->type);
				}

				$totaltypes = count($newtypes) + $lenothertypes;
			} ?>
			
			<h4><?=__("Who Uses This")?></h4>
			<ul class="multiple-items">
			<?php foreach ($newtypes as $s): ?>
			<li><?php echo $s;?></li>
			<?php endforeach; ?>
			<?php if (isset($othertypes)) : ?>
				<?php foreach ($othertypes as $s): ?>
					<li><?php echo trim($s);?></li>
				<?php endforeach; ?>
			<?php endif; ?>	


			<?php if ($totaltypes > 3): ?>
				<li class="view-more-link"><a href="#"><?=__("View More")?></a></li>
			<?php endif; ?>
		</ul><?php endif;?>
		
		<?php if($sitelang->hasRegions == 1):?>
		<?php if(!empty($word->regions)):?>
			<?php $newregions = [];
			foreach ($word->regions as $key => $region){
					$newregions[$key] = __($region->region);
			} ?>
			
			<h4><?=__("Regions")?></h4>
			<ul class="multiple-items">
			<?php foreach ($newregions as $s): ?>
			<li><?php echo $s;?></li>
			<?php endforeach; ?>
		</ul><?php endif;?>
		<?php endif;?>
			
		<?php if($sitelang->hasDictionaries == 1):?>
		<?php if(!empty($word->dictionaries)):?>
			<h4><?=__("Dictionaries")?></h4>
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
		</div> <!--// TODO check what this div is doing-->
		<div class='wnotes'>
		<?php if(!empty($word->notes)):?>
			<h4><?=__("Notes")?></h4>
			<ul>
				<li class='notesli'><?php echo $word->notes;?></li> <!--// // TODO should check for <p></br></p> -->
			</ul>
		<?php endif;?>
	</div>
		</div>

	<div class="c wordedit">
		<p class="m0">
		<?=$this->Html->link('<i class="fa-solid fa-pen-to-square"></i>' . __(' Edit'), '/words/edit/' .$word->id,
											['class' => 'button blue', 'escape' => false]);?>
		&nbsp;&nbsp;&nbsp;&nbsp;<?=__("Something missing from this entry? Inaccurate? Feel free to suggest an edit.")?></p>
	</div>
</section>

