<section id="main">
	<div class="page-header2 group">
		<h2 class="left"><?php echo $word->spelling;?></h2>
			<div style="display: flex; justify-content: flex-end; align-items: center; flex-wrap: wrap">
				<?php
				$currentUrl = $this->Url->build(null, ['fullBase' => true]);
				$wordText = h($word->spelling); 
				$encodedText = urlencode("Check out this word: " . $wordText);
				$encodedUrl = urlencode($currentUrl);
				?>
		<ul class="editbuttons">
			<li>
				<button id="shareToggle" class="button blue nl">
					<i class="fa-solid fa-share-nodes"></i> <?= __('Share') ?>
				</button>
				<div class="flexDiv">
					<div class="shareDropdown" id="shareDropdown">
					<div class="trianglePointer"></div>
						<a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>" 
							target="_blank" 
							aria-label="Share on Facebook">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#3b5998" viewBox="0 0 24 24">
								<path d="M22.675 0h-21.35C.595 0 0 .593 0 1.326v21.348C0 23.406.595 24 1.326 24h11.495V14.708h-3.13v-3.622h3.13V8.413c0-3.1 1.893-4.788 4.658-4.788 1.325 0 2.463.098 2.794.142v3.24l-1.918.001c-1.504 0-1.794.715-1.794 1.763v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.674V1.326C24 .593 23.406 0 22.675 0z"/>
							</svg>
							<span class="shareLink">
								Facebook
							</span>
						</a>
						<a href="https://twitter.com/intent/tweet?url=<?= $encodedUrl ?>&text=<?= $encodedText ?>" 
							target="_blank" 
							aria-label="Share on Twitter">
							<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path d="M13.903 10.469 21.348 2h-1.764l-6.465 7.353L7.955 2H2l7.808 11.12L2 22h1.764l6.828-7.765L16.044 22H22l-8.097-11.531Zm-2.417 2.748-.791-1.107L4.4 3.3h2.71l5.08 7.11.791 1.107 6.604 9.242h-2.71l-5.389-7.542Z"></path>
							</svg>
							<span class="shareLink">
								X
							</span>
						</a>
						<a href="https://api.whatsapp.com/send?text=<?= $encodedText ?>%20<?= $encodedUrl ?>" 
							target="_blank" 
							aria-label="Share on WhatsApp">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" fill="#25D366">
								<path d="M16 .003C7.163.003 0 7.16 0 16c0 2.83.744 5.53 2.145 7.936L0 32l8.293-2.127A15.89 15.89 0 0 0 16 32c8.837 0 16-7.16 16-16s-7.163-15.997-16-15.997zm0 29.126c-2.506 0-4.95-.658-7.094-1.905l-.508-.298-4.926 1.262 1.307-4.813-.33-.522a12.994 12.994 0 0 1-1.983-6.975c0-7.183 5.844-13.027 13.027-13.027 3.48 0 6.749 1.355 9.207 3.813A12.94 12.94 0 0 1 29.03 16c0 7.183-5.844 13.129-13.03 13.129zM23.156 19.1c-.366-.183-2.16-1.065-2.496-1.186-.336-.121-.583-.183-.83.183-.244.366-.954 1.187-1.17 1.431-.214.244-.429.275-.793.092-.366-.183-1.545-.571-2.942-1.823-1.087-.967-1.821-2.158-2.033-2.524-.214-.366-.023-.564.16-.747.165-.163.366-.427.55-.641.183-.213.244-.366.366-.61.122-.244.061-.458 0-.641-.061-.183-.793-1.915-1.087-2.623-.287-.685-.579-.593-.793-.603-.204-.008-.438-.01-.671-.01-.244 0-.641.092-.976.458s-1.28 1.251-1.28 3.049c0 1.798 1.31 3.538 1.494 3.782.183.244 2.577 3.938 6.248 5.515.873.377 1.553.603 2.085.77.875.278 1.671.239 2.3.145.702-.104 2.16-.881 2.464-1.731.306-.85.306-1.58.214-1.731-.091-.153-.336-.244-.702-.427z"/>
							</svg>
							<span class="shareLink">
								WhatsApp
							</span>
						</a>
						<a href="https://bsky.app/intent/compose?text=<?= $encodedText ?>%20<?= $encodedUrl ?>" 
							target="_blank" 
							aria-label="Share on Bluesky">
							<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#0085FF" height="24" width="24">
								<path d="M12 10.8c-1.087 -2.114 -4.046 -6.053 -6.798 -7.995C2.566 0.944 1.561 1.266 0.902 1.565 0.139 1.908 0 3.08 0 3.768c0 0.69 0.378 5.65 0.624 6.479 0.815 2.736 3.713 3.66 6.383 3.364 0.136 -0.02 0.275 -0.039 0.415 -0.056 -0.138 0.022 -0.276 0.04 -0.415 0.056 -3.912 0.58 -7.387 2.005 -2.83 7.078 5.013 5.19 6.87 -1.113 7.823 -4.308 0.953 3.195 2.05 9.271 7.733 4.308 4.267 -4.308 1.172 -6.498 -2.74 -7.078a8.741 8.741 0 0 1 -0.415 -0.056c0.14 0.017 0.279 0.036 0.415 0.056 2.67 0.297 5.568 -0.628 6.383 -3.364 0.246 -0.828 0.624 -5.79 0.624 -6.478 0 -0.69 -0.139 -1.861 -0.902 -2.206 -0.659 -0.298 -1.664 -0.62 -4.3 1.24C16.046 4.748 13.087 8.687 12 10.8Z" stroke-width="1"></path>
							</svg>
							<span class="shareLink">
								Bluesky
							</span>
						</a>
					</div>
				</div>
			</li>
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
					if($this->Identity->isLoggedIn() && count($word->pronunciations) == 0) {
						echo $this->Html->link('<i class="fas fa-microphone"></i>' . __(' Record a Pronunciation'), '/pronunciations/add/' .$word->id,
												['class' => 'button blue nl', 'escape' => false]);
					}
					elseif (count($word->pronunciations) == 0) {
						echo $this->Html->link('<i class="fas fa-microphone"></i>' . __(' Record a Pronunciation'), '/login?redirect=/pronunciations/add/' .$word->id,
						['class' => 'button blue nl', 'escape' => false]);
					}
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
	if($this->Identity->isLoggedIn() && count($word->pronunciations) == 0) {
		echo $this->Html->link(__('Record a Pronunciation'), '/pronunciations/add/' .$word->id, ['class' => 'button blue nl', 'escape' => false]);
	}
	elseif (count($word->pronunciations) == 0) {
		echo $this->Html->link(__('Record a Pronunciation'), '/login?redirect=/pronunciations/add/' .$word->id, ['class' => 'button blue nl', 'escape' => false]);
	}
	?>
	</div>
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

				if($type->id != 999 && $lenothertypes == 0){ // logic to exclude "other" from appearing in list
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
				<li class='notesli'><?php echo $word->notes;?></li> <!--// TODO should check for <p></br></p> -->
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
<div class="shareFooter">
  Share this word:<br>
<div class="shareFooterIcons">
	<a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>" 
		target="_blank" 
		aria-label="Share on Facebook">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#3b5998" viewBox="0 0 24 24">
			<path d="M22.675 0h-21.35C.595 0 0 .593 0 1.326v21.348C0 23.406.595 24 1.326 24h11.495V14.708h-3.13v-3.622h3.13V8.413c0-3.1 1.893-4.788 4.658-4.788 1.325 0 2.463.098 2.794.142v3.24l-1.918.001c-1.504 0-1.794.715-1.794 1.763v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.674V1.326C24 .593 23.406 0 22.675 0z"/>
		</svg>
	</a>
	<a href="https://twitter.com/intent/tweet?url=<?= $encodedUrl ?>&text=<?= $encodedText ?>" 
		target="_blank" 
		aria-label="Share on Twitter">
		<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<path d="M13.903 10.469 21.348 2h-1.764l-6.465 7.353L7.955 2H2l7.808 11.12L2 22h1.764l6.828-7.765L16.044 22H22l-8.097-11.531Zm-2.417 2.748-.791-1.107L4.4 3.3h2.71l5.08 7.11.791 1.107 6.604 9.242h-2.71l-5.389-7.542Z"></path>
		</svg>
	</a>
	<a href="https://api.whatsapp.com/send?text=<?= $encodedText ?>%20<?= $encodedUrl ?>" 
		target="_blank" 
		aria-label="Share on WhatsApp">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" fill="#25D366">
			<path d="M16 .003C7.163.003 0 7.16 0 16c0 2.83.744 5.53 2.145 7.936L0 32l8.293-2.127A15.89 15.89 0 0 0 16 32c8.837 0 16-7.16 16-16s-7.163-15.997-16-15.997zm0 29.126c-2.506 0-4.95-.658-7.094-1.905l-.508-.298-4.926 1.262 1.307-4.813-.33-.522a12.994 12.994 0 0 1-1.983-6.975c0-7.183 5.844-13.027 13.027-13.027 3.48 0 6.749 1.355 9.207 3.813A12.94 12.94 0 0 1 29.03 16c0 7.183-5.844 13.129-13.03 13.129zM23.156 19.1c-.366-.183-2.16-1.065-2.496-1.186-.336-.121-.583-.183-.83.183-.244.366-.954 1.187-1.17 1.431-.214.244-.429.275-.793.092-.366-.183-1.545-.571-2.942-1.823-1.087-.967-1.821-2.158-2.033-2.524-.214-.366-.023-.564.16-.747.165-.163.366-.427.55-.641.183-.213.244-.366.366-.61.122-.244.061-.458 0-.641-.061-.183-.793-1.915-1.087-2.623-.287-.685-.579-.593-.793-.603-.204-.008-.438-.01-.671-.01-.244 0-.641.092-.976.458s-1.28 1.251-1.28 3.049c0 1.798 1.31 3.538 1.494 3.782.183.244 2.577 3.938 6.248 5.515.873.377 1.553.603 2.085.77.875.278 1.671.239 2.3.145.702-.104 2.16-.881 2.464-1.731.306-.85.306-1.58.214-1.731-.091-.153-.336-.244-.702-.427z"/>
		</svg>
	</a>
	<a href="https://bsky.app/intent/compose?text=<?= $encodedText ?>%20<?= $encodedUrl ?>" 
		target="_blank" 
		aria-label="Share on Bluesky">
		<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#0085FF" height="24" width="24">
			<path d="M12 10.8c-1.087 -2.114 -4.046 -6.053 -6.798 -7.995C2.566 0.944 1.561 1.266 0.902 1.565 0.139 1.908 0 3.08 0 3.768c0 0.69 0.378 5.65 0.624 6.479 0.815 2.736 3.713 3.66 6.383 3.364 0.136 -0.02 0.275 -0.039 0.415 -0.056 -0.138 0.022 -0.276 0.04 -0.415 0.056 -3.912 0.58 -7.387 2.005 -2.83 7.078 5.013 5.19 6.87 -1.113 7.823 -4.308 0.953 3.195 2.05 9.271 7.733 4.308 4.267 -4.308 1.172 -6.498 -2.74 -7.078a8.741 8.741 0 0 1 -0.415 -0.056c0.14 0.017 0.279 0.036 0.415 0.056 2.67 0.297 5.568 -0.628 6.383 -3.364 0.246 -0.828 0.624 -5.79 0.624 -6.478 0 -0.69 -0.139 -1.861 -0.902 -2.206 -0.659 -0.298 -1.664 -0.62 -4.3 1.24C16.046 4.748 13.087 8.687 12 10.8Z" stroke-width="1"></path>
		</svg>
	</a>
</div></div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("shareToggle");
    const dropdown = document.getElementById("shareDropdown");

    dropdown.style.display = "none";

    toggle.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdown.style.display = dropdown.style.display === "none" ? "flex" : "none";
    });

    document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target) && e.target !== toggle) {
            dropdown.style.display = "none";
        }
    });

    dropdown.addEventListener("click", (e) => e.stopPropagation());
});
</script>
</section>

