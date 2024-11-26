<?php echo $this->Html->script('nav', array('block' => 'js_bottom'));?>
<section id="main">
	<nav id="crumbs" class="group">
		<ul class="right">
			<li><?=$this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> ' . __('Add a new word'), '/add',
											['class' => 'button blue', 'escape' => false]);?></li>
		</ul>
	</nav>

	<div class="dropdown-container">
	<?php $ortdarray = [[$sitelang->hasOrigins, $ortd["origins"], "Origins"], 
						[$sitelang->hasRegions, $ortd["regions"], "Regions"], 
						[$sitelang->hasTypes, $ortd["types"], "Types"],
						[$sitelang->hasDictionaries, $ortd["dictionaries"], "Dictionaries"]];
			$i = 0;
			$j = 3;

	?>
	<?php foreach($ortdarray as $ortdarray2):?>
		<?= '<div class="dropdown3">' ?>
		<?= '<button onclick="toggleDropdown(\'dropdown' . $j . '\')" class="dropbtn3">&#9660; Select ' . $ortdarray2[2] . '</button>' ?>
		<?= '<div id="checkboxes' . $j . '" class="dropdown-content3 checkboxesclass">'?>
		<?php if($ortdarray2[0] and $ortdarray2[2] !== 'Types'): ?>
			<?php 
				$ortddata = [];
				foreach ($ortdarray2[1] as $k => $v){
							$ortddata[$k] = __($v);
						} 
			?>
			<?php foreach ($ortddata as $id => $o):?>	
				<?php echo '<label><input type="checkbox" value="' . $ortdarray2[2] . '_' . $id . '" onchange="checkboxChanged(\'dropdown' . $j . '\', this)"> ' . $o . '</label>'; ?>
			<?php endforeach;?>	
			<?php if($ortdarray2[0] and $ortdarray2[2] == 'Dictionaries'): ?>
				<?php echo '<label><input type="checkbox" value="none" onchange="checkboxChanged(\'dropdown' . $j . '\', this)"> None</label>'; ?>
			<?php endif; ?>	
				<!-- Add more options as needed -->
			
	<?php elseif($ortdarray2[2] == 'Types'): ?>
		<?php $otherarray = [];
				foreach ($ortdarray2[1] as $type){
					if (isset($type->category)){
						echo __($type->category) . "<br>";
					}
					if (is_array($type->types)) {
						foreach ($type->types as $subtype) {
							// If it's a subarray, list all its values in the first index
							//$output = implode(", ", $type);
							if (str_contains($subtype->type, ":")) {
								$boldMainName = explode(":", $subtype->type);
								$boldMainName[0] = $boldMainName[0] . ":";
							} else {
								$boldMainName = [$subtype->type, ""];
							}
							echo '<label><input type="checkbox" value="' . $ortdarray2[2]  . '_' . $subtype->id . '" onchange="checkboxChanged(\'dropdown' . $j . '\', this)"> ' . __($boldMainName[0]) .  __($boldMainName[1]) . '</label>';
							#echo "<li>". $this->Html->link("<span class='boldname'>" . __($boldMainName[0]) . "</span>"
							#	. __($boldMainName[1]), '/words?use='.$subtype->id, ['escape' => false]) . "</li>";
						}
						echo "<br>";
					} else {
						// If it's not a subarray, simply print the value
							array_push($otherarray, $type);
					}
					//echo "<br>";
				} 
				if (count($otherarray) > 0) {
					if($sitelang->hasTypeCategories) {
						echo __("Other") . "<br>";
					}
						foreach ($otherarray as $othertype) {
							echo "<li>".  $this->Html->link(__($othertype->type), '/words?use='.$othertype->id) . "</li>";
						}
					}
				?>
	<?php endif; ?>
			
	<?= '<label><input type="checkbox" value="other" onchange="checkboxChanged(\'dropdown' . $j . '\', this)"> Other</label>'?>
	</div>
	</div>
	<?php $i++;
			$j++; ?>
	<?php endforeach; ?>
	</div> <!--end of dropdown container-->
	

	<?php if (sizeof($words) == 0):?>
		<div class="c content">
			<p><?=__("No words were found. Refine your search options above.")?></p>
		</div>
	<?php else:?>

		<div id="browse_info" class="group">
				
			<div class="line-container">
				<div id="checkedOptionsDiv"><?php 
					if (array_key_first($cc) == 'dictionaries' && $cc['dictionaries'] == 'none') {
						echo "Checked options: Words not in any other dictionary.";
					}
					elseif (in_array(array_key_first($cc),['origins', 'regions', 'dictionaries'])){
						echo "Checked options: " . $ortd[array_key_first($cc)][$cc[array_key_first($cc)]];
					} elseif (array_key_first($cc) == 'types') {
						echo "Checked options: " . $ortd['toptypes'][$cc['types']];
					} else {
						echo "";
					}
						?>
				</div>
				<?php if ($isPaginated): ?>
				<button id="displayAllButton" class="button blue">Display All</button>

				<script>
					document.getElementById('displayAllButton').addEventListener('click', function () {
						const currentUrl = new URL(window.location.href); // Get the current URL
						currentUrl.searchParams.set('displayType', 'all'); // Add or update the displayType parameter
						window.location.href = currentUrl.toString(); // Redirect to the new URL
					});
				</script>
				<?php endif; ?>	
			</div>
		</div>
		<ul class="word-list">
		<?php foreach ($words as $word): ?>
			<li class="group">
				<div class="word-main">
					<h3><?php echo $this->Html->link($word->spelling, '/words//'.$word->id); ?></h3>
					<?php echo $this->Html->link(__('SEE FULL ENTRY') . ' <i class="fa fa-caret-down"></i>', '/words//'.$word->id, ['class' => 'noborder', 'escape' => false]); ?>

				

				</div>
			</li>
		<?php endforeach; ?>

		</ul>
		<?php if ($isPaginated): ?>
		<div class="pagination">

			<?php if ($this->Paginator->hasPrev()) :?>
				<?= $this->Paginator->prev(' << ' . __('previous'));?>
			<?php endif ?>
			<?php if ($this->Paginator->hasNext()) :?>
				<?= $this->Paginator->next(' >> ' . __('next'));?>
			<?php endif ?>	

		</div>
		<?php endif; ?>
	<?php endif;?>


</section>
