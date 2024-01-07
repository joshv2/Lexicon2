<?php echo $this->Html->script('nav', array('block' => 'js_bottom'));?>
<div class="dropdown-container">
<?php $ortdarray = [[$sitelang->hasOrigins, $ortd["origins"], "Origins"], 
					[$sitelang->hasRegions, $ortd["regions"], "Regions"], 
					[$sitelang->hasTypes, $ortd["types"], "Types"],
					[$sitelang->hasDictionaries, $ortd["dictionaries"], "Dictionaries"]];
		$i = 0;
		$j = 3;
?>
<?php foreach($ortdarray as $ortdarray2):?>
	<?= '<div class="dropdown' .  $j . '">' ?>
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
			<?php echo '<label><input type="checkbox" value="' . $id . '" onchange="checkboxChanged(\'dropdown' . $j . '\', this)"> ' . $o . '</label>'; ?>
		<?php endforeach;?>	
		
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
						echo '<label><input type="checkbox" value="' . $subtype->id . '" onchange="checkboxChanged(\'dropdown' . $j . '\', this)"> ' . __($boldMainName[0]) .  __($boldMainName[1]) . '</label>';
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
<nav id="crumbs" class="group">
	<ul class="right">
		<li><?=$this->Html->link('<i class="icon-plus-sign"></i>' . __('Add a new word'), '/add',
										['class' => 'button blue', 'escape' => false]);?></li>
	</ul>
</nav>





<section id="main">
	<nav id="mobilefilterbrowse">
		<ul class="browse_nav group left">
		<?php if($sitelang->hasOrigins): ?>
			<li class="first main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['origin']) ? __('All Origins') : '<b>' . __('Origin') .':</b> '. __($origins[$current_condition['origin']]); ?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('origin');?>"><?php echo __('All'); ?></a></li>
							<?php foreach ($origins as $k => $o): ?>
							<li><a href="<?php echo $this->QueryString->add('origin', $k); ?>"><?php echo __($o);?></a></li>
							<?php endforeach;?>
						</ul>
					</div>
				</div>
			</li>
			<?php endif; ?>
			<?php if($sitelang->hasDictionaries): ?>
			<li class="main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['dictionary']) ? 
					__('All Dictionaries') : '<b>' . __('Dictionary') . ':</b> ' . __($dictionaries[$current_condition['dictionary']]);?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('dictionary'); ?>"><?php echo __('All'); ?></a></li>
							<?php foreach ($dictionaries as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('dictionary', $k); ?>"><?php echo __($o);?></a></li><?php endforeach;?>
						</ul>
					</div>
				</div>
			</li>
			<?php endif; ?>
			<?php if($sitelang->hasTypes): ?>
			<li class="main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['use']) ? __('All Uses') : '<b>'. __('Use') . ':</b> ' . __($types[$current_condition['use']]);?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('use');?>"><?php echo __('All'); ?></a></li>
						<?php foreach ($types as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('use', $k); ?>"><?php echo __($o);?></a></li><?php endforeach;?>
						</ul>
					</div>
				</div>
			</li>
			<?php endif; ?>
			<?php if($sitelang->hasRegions): ?>
			<li class="main last">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['region']) ? __('All Regions') : '<b>'. __('Region') .':</b> ' . __($regions[$current_condition['region']]);?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('region');?>"><?php echo __('All');?></a></li>
						<?php foreach ($regions as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('region', $k); ?>"><?php echo __($o);?></a></li><?php endforeach;?>
						</ul>
					</div>
				</div>
			</li>
			<?php endif; ?>
		</ul>
	</nav>


	<nav id="browse">
		<ul class="browse_nav group left">
		<?php if($sitelang->hasOrigins): ?>	
			<li class="first main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['origin'])? __('All Origins') : '<b>'. __('Origin') .':</b> '. __($origins[$current_condition['origin']]); ?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('origin');?>"><?php echo __('All');?></a></li>
							<?php foreach ($origins as $k => $o): ?>
							<li><a href="<?php echo $this->QueryString->add('origin', $k); ?>"><?php echo __($o);?></a></li>
							<?php endforeach;?>
						</ul>
					</div>
				</div>
			</li>
			<?php endif; ?>
			<?php if($sitelang->hasDictionaries): ?>
			<li class="main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['dictionary']) ? 
					__('All Dictionaries') : '<b>'. __('Dictionary') .':</b> ' . __($dictionaries[$current_condition['dictionary']]);?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('dictionary'); ?>"><?php echo __('All');?></a></li>
							<?php foreach ($dictionaries as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('dictionary', $k); ?>"><?php echo __($o);?></a></li><?php endforeach;?>
						</ul>
					</div>
				</div>
			</li>
			<?php endif; ?>
			<?php if($sitelang->hasTypes): ?>
			<li class="main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['use']) ? __('All Uses') : '<b>' . __('Use') .':</b> ' . __($types[$current_condition['use']]['type']);?></span> <!--$current_condition['use']-->
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('use');?>"><?php echo __('All');?></a></li>
						<?php foreach ($types as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('use', $k); ?>"><?php echo __($o['type']);?></a></li><?php endforeach;?>
						</ul>
					</div>
				</div>
			</li>
			<?php endif; ?>
			<?php if($sitelang->hasRegions): ?>
			<li class="main last">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['region']) ? __('All Regions') : '<b>'. __('Region') .':</b> ' . __($regions[$current_condition['region']]);?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('region');?>"><?php echo __('All');?></a></li>
						<?php foreach ($regions as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('region', $k); ?>"><?php echo __($o);?></a></li><?php endforeach;?>
						</ul>
					</div>
				</div>
			</li>
			<?php endif; ?>
		</ul>
	</nav>
	

	<?php if (sizeof($words) == 0):?>
		<div class="c content">
			<p><?=__("No words were found. Refine your search options above.")?></p>
		</div>
	<?php else:?>

		<div id="browse_info" class="group">
			<p id="paging_info">
				<?php echo $this->Paginator->counter(__('{{start}} - {{end}} of {{count}}'));?>
			</p>
		</div>
		
		<ul class="word-list">
		<?php foreach ($words as $word): ?>
			<li class="group">
				<div class="word-main">
					<h3><?php echo $this->Html->link($word->spelling, '/words//'.$word->id); ?></h3>
					<?php echo $this->Html->link(__('SEE FULL ENTRY') . ' <i class="fa fa-caret-down"></i>', '/words//'.$word->id, ['class' => 'noborder', 'escape' => false]); ?>
					
					<!--<p class="definition"><?php echo sizeof($word->definitions) > 0 ? $word->definitions[0]->definition : '';?></p>-->
				</div>
			</li>
		<?php endforeach; ?>

		</ul>
		<div class="pagination">
			<?php if ($this->Paginator->hasPrev()) :?>
				<?= $this->Paginator->prev(' << ' . __('previous'));?>
			<?php endif ?>
			<?php if ($this->Paginator->hasNext()) :?>
				<?= $this->Paginator->next(' >> ' . __('next'));?>
			<?php endif ?>	
		</div>
	<?php endif;?>


</section>