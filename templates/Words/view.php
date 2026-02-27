<?php
	$this->assign('og_tags', '
		<meta property="og:title" content="' . h($spelling) . ' | ' . $sitelang->name . '" />
		<meta property="og:description" content="Check out ' .  h($spelling) . ' on the ' . $sitelang->name . '" />
		<meta property="og:url" content="' . $this->Url->build(null, ['fullBase' => true]) . '" />
	');
	
		
?>
	
	<div class="page-header2">

		<div class="left-group">
		<div class="spelling"><?= $spelling;?></div>
		</div>
		<div class="right-group">
		<ul class="editbuttons">
			<li>
				<button id="shareToggle" class="btn-blue">
					<i class="fa-solid fa-share-nodes"></i>&nbsp;<?= __('Share') ?>
				</button>
				<div class="flexDiv">
					<div class="shareDropdown" id="shareDropdown">
						<div class="trianglePointer"></div>
						<?= $this->cell('Share', ['word' => $word_id, 'dropdown' => true]) ?>
					</div>
				</div>
			</li>
			<?php if ($this->Identity->isLoggedIn()): ?>
				<li>
					<?= $this->AuthLink->postlink(
						__('<i class="fa-solid fa-trash"></i>&nbsp;Delete'),
						[
							'prefix' => false,
							'controller' => 'Words',
							'action' => 'delete', $word_id
						],
						[
							'escape' => false,
							'class' => 'btn-red',
							'confirm' => 'Are you sure you want to delete ' . $spelling . '?'
						]
					) ?>
				</li>
			<?php endif; ?>

			
			<?php 
			$pronunciations_count = count($pronunciations);
			if ($pronunciations_count == 0): ?>
				<li>
					<?= $this->Html->link(
						'<i class="fas fa-microphone"></i>&nbsp;' . __('Record a Pronunciation'),
						'/pronunciations/add/' . $word_id,
						['class' => 'button blue nl', 'escape' => false]
					) ?>
				</li>
			<?php endif; ?>
			<li>
				<?= $this->Html->link(
					'<i class="fa-solid fa-pen-to-square"></i>&nbsp;' . __('Edit'),
					'/words/edit/' . $word_id,
					['class' => 'btn-blue', 'escape' => false]
				) ?>
			</li>
		</ul>
		</div>
	</div>
	<div class="page-header group2">
		<?=$this->Html->link(__('Edit'), '/words/edit/' .$word_id);?>
				</br>
				<?php 
					if ($pronunciations_count == 0) {
							echo $this->Html->link(__('Record a Pronunciation'), '/pronunciations/add/' .$word_id, ['class' => 'button blue nl', 'escape' => false]);
					}
				?>
	</div>

	<div class='c'>
	<div class="word">
		
		<?php if (!empty($pronunciations) || ($this->Identity->isLoggedIn() && (!empty($hasPendingPronunciations) && $hasPendingPronunciations))) : ?>
			<h4>Pronunciations</h4>

			<div class="pronunciation-section">
				<!-- LEFT SIDE: TABLE -->
				<div class="table-container">
					<table>
						<?php $i = 0; ?>
						<?php foreach ($pronunciations as $p): ?>
							<?php if (1 == $p['approved']): ?>
								<?php 
									if (empty($p['sound_file'])) {
										$audioPlayer = '';
									} else {
										$audioPlayer = '<a id="play-pause-button-' . $i . '">
											<i class="fa-solid fa-volume-up"></i> 
											<span id="listen">listen</span>
											</a>' .
											$this->Html->media(
												str_replace('.webm', '.mp3', $p['sound_file']),
												[
													'pathPrefix' => 'recordings/',
													'controls',
													'class' => 'audioplayers',
													'id' => 'audioplayer' . $i
												]
											);
									}
								?>
								<?= $this->Html->tableCells(
									[[$p['spelling'], "(" . $p['pronunciation'] . ")", $audioPlayer]],
									['class' => 'pronunciationtr']
								); ?>
							<?php endif; ?>
							<?php $i++; ?>
						<?php endforeach; ?>
					</table>
				</div>

				<!-- RIGHT SIDE: BUTTONS -->
				<div class="buttons-container">
					<p>
						<?= $this->Html->link(
							'<i class="fas fa-microphone"></i> ' . __('Record a Pronunciation'),
							'/pronunciations/add/' . $word_id,
							['class' => 'button blue', 'escape' => false]
						); ?>
					</p>

					<?php if ($this->Identity->isLoggedIn()): ?>
						<p>
							<?= $this->Html->link(
								'<i class="fa-solid fa-pen-to-square"></i> ' . __('Manage Pronunciations'),
								'/pronunciations/manage/' . $word_id,
								['class' => 'button blue', 'escape' => false]
							); ?>
						</p>
					<?php endif; ?>
				</div>

			</div>
		<?php endif; ?>
		<?= $this->element('word_list', [
			'header' => __("Definitions"),
			'newortd' => array_column($definitions, 'definition'),
			'otherortd' => [],
			'totalortd' => $total_definitions,
			'edit_controller' => 'definitions',
		]) ?>
		

		<?php if (!empty($sentences)): ?>
		<h4><?= __("Example Sentences") ?></h4>

		<div class="sentences-wrapper">

			<!-- LEFT COLUMN -->
			<div class="sentences-section">
				<?php if (!empty($sentences)): ?>
					<ul class="sentences multiple-items">
						<?php $audioIndex = count($pronunciations) + 1; ?>
						<?php foreach ($sentences as $sentence): ?>
							<?php
								$recordingLinks = [];
								$recordingCount = 1;

								foreach ($sentence['sentence_recordings'] as $rec) {
									if (!$rec['approved']) continue;

									$audioTag = $this->Html->media(
										str_replace('.webm', '.mp3', $rec['sound_file']),
										[
											'pathPrefix' => 'recordings/',
											'controls',
											'class' => 'audioplayers',
											'id'    => 'audioplayer' . $audioIndex,
										]
									);

									$recordingLinks[] =
										"<a id='play-pause-button-{$audioIndex}'>
											<i class='fa-solid fa-volume-up'></i>
											<span id='listen'>" . __("Recording") . " {$recordingCount}</span>
										</a>" .
										$audioTag;

									$audioIndex++;
									$recordingCount++;
								}
							?>

							<li class="pronunciationtr2">
								<?= $sentence['sentence']; ?>

								<?php if ($recordingLinks): ?>
									<span class="recordinglist">
										<?= __('Listen to recordings of this sentence') ?>: (
										<?= implode($recordingLinks) ?> )
									</span>
								<?php endif; ?>

								<?php if ($this->Identity->isLoggedIn()): ?>
									<?= $this->AuthLink->link(
										__(' Manage Recordings'),
										['prefix' => false, 'controller' => 'SentenceRecordings',
										'action' => 'manage', $word_id, $sentence['id']]
									); ?>
								<?php endif; ?>
							</li>

						<?php endforeach; ?>

						<?php if ($sentences_count > 3): ?>
							<li class="view-more-link"><a href="#"><?= __("View More") ?></a></li>
						<?php endif; ?>

					</ul>
							<?php endif; ?>
				
			</div>

			<!-- RIGHT COLUMN (BUTTON) -->
			<div class="record-button-section">
    <div class="buttons-container">
        <p>
            <?php
                if ($sentences_count == 1) {
                    echo $this->Html->link(
                        '<i class="fas fa-microphone"></i> ' . __('Record a Sentence'),
                        '/SentenceRecordings/add/' . $sentences[0]['id'],
                        ['class' => 'button blue', 'escape' => false]
                    );
                } else {
                    echo $this->Html->link(
                        '<i class="fas fa-microphone"></i> ' . __('Record a Sentence'),
                        '/SentenceRecordings/choose/' . $word_id,
                        ['class' => 'button blue', 'id' => 'convert', 'escape' => false]
                    );
                }
            ?>
        </p>
		<?php if ($this->Identity->isLoggedIn()): ?>
        <p>
            <?= $this->Html->link(
                '<i class="fa-solid fa-plus"></i> ' . __('Add a Sentence'),
                '/sentences/add/' . $word_id,
                ['class' => 'button blue', 'escape' => false]
            ) ?>
        </p>
		<?php endif; ?>
    </div>
</div>

		</div>
		<?php endif; ?>
		

		<?= $this->element('word_list', [
			'header' => __("Languages of Origin"),
			'newortd' => $new_origins,
			'otherortd' => $other_origins ?? [],
			'totalortd' => $total_origins,
			'edit_controller' => 'origins',
		]) ?>

		<?php if(!empty($etymology)):?>
			<h4><?=__("Etymology")?></h4>
			<ul class='sentences'>
			<li><?= $etymology; ?></li>
			<ul>
		<?php endif;?>


		<?= $this->element('word_list', [
			'header' => __("Who Uses This"),
			'newortd' => $new_types,
			'otherortd' => $other_types ?? [],
			'totalortd' => $total_types,
			'edit_controller' => 'types',
		]) ?>
		
		
		<?php if($sitelang->hasRegions == 1):?>
			<?php if(!empty($new_regions)):?>
				<?= $this->element('word_list', [
					'header' => __("Regions"),
					'newortd' => $new_regions,
					'otherortd' => $other_regions ?? [],
					'totalortd' => $total_regions,
					'edit_controller' => 'regions',
				]) ?>
			<?php endif;?>
		<?php endif;?>
			
		<?php if($sitelang->hasDictionaries == 1):?>
			<?= $this->element('word_list', [
				'header' => __("Dictionaries"),
				'newortd' => $new_dictionaries,
				'otherortd' => $other_dictionaries ?? [],
				'totalortd' => $total_dictionaries,
				'edit_controller' => 'dictionaries',
			]) ?>
		<?php endif;?>

		<?= $this->element('word_alternates', [
			'alternates' => $alternates,
			'spellingList' => $spellingList,
		]) ?>


		</div> <!--// TODO check what this div is doing-->
		<div class='wnotes'>
		<?php if(!empty($notes)):?>
			<h4><?=__("Notes")?></h4>
			<ul>
				<li class='notesli'><?= $notes;?></li> <!--// TODO should check for <p></br></p> -->
			</ul>
		<?php endif;?>
	</div>
		</div>

	<div class="c wordedit">
		<p class="m0">
		<?=$this->Html->link('<i class="fa-solid fa-pen-to-square"></i>' . __(' Edit'), '/words/edit/' .$word_id,
											['class' => 'button blue', 'escape' => false]);?>
		&nbsp;&nbsp;&nbsp;&nbsp;<?=__("Something missing from this entry? Inaccurate? Feel free to suggest an edit.")?></p>
	</div>
<div class="shareFooter">
	Share this word:<br>
	<div class="shareFooterIcons">
		<?= $this->cell('Share', [$spelling]) ?>
	</div>
</div>


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

