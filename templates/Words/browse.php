<?php echo $this->Html->script('nav', array('block' => 'js_bottom'));?>

<nav id="crumbs" class="group">
	<ul class="right">
		<li><?=$this->Html->link('<i class="icon-plus-sign"></i>' . __('Add a new word'), '/add',
										['class' => 'button blue', 'escape' => false]);?></li>
	</ul>
</nav>
<div class="checkbox-container">
  <div class="category">
    <h3 class="category-header">Category 1</h3>
    <div class="checkboxes">
      <input type="checkbox" id="checkbox1-1" name="checkbox1-1">
      <label for="checkbox1-1">Checkbox 1.1</label><br>
      <!-- Add more checkboxes for Category 1 as needed -->
    </div>
  </div>

  <div class="category">
    <h3 class="category-header">Category 2</h3>
    <div class="checkboxes">
      <input type="checkbox" id="checkbox2-1" name="checkbox2-1">
      <label for="checkbox2-1">Checkbox 2.1</label><br>
      <!-- Add more checkboxes for Category 2 as needed -->
    </div>
  </div>

  <div class="category">
    <h3 class="category-header">Category 3</h3>
    <div class="checkboxes">
      <input type="checkbox" id="checkbox3-1" name="checkbox3-1">
      <label for="checkbox3-1">Checkbox 3.1</label><br>
      <!-- Add more checkboxes for Category 2 as needed -->
    </div>
  </div>
  <!-- Repeat the above structure for Categories 3 and 4 -->
  <div class="category">
    <h3 class="category-header">Category 4</h3>
    <div class="checkboxes">
      <input type="checkbox" id="checkbox4-1" name="checkbox4-1">
      <label for="checkbox4-1">Checkbox 4.1</label><br>
      <!-- Add more checkboxes for Category 2 as needed -->
    </div>
  </div>
</div>

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
						<span><?php echo empty($current_condition['use']) ? __('All Uses') : '<b>'. __('Use') . ':</b> ' . __($types[$current_condition['use']]['type']);?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('use');?>"><?php echo __('All'); ?></a></li>
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