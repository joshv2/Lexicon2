<?php echo $this->Html->script('nav', array('block' => 'js_bottom'));?>

<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
	<ul class="right">
		<li><?=$this->Html->link('<i class="icon-plus-sign"></i> Add a new word', '/add',
										['class' => 'button blue', 'escape' => false]);?></li>
	</ul>
</nav>

<section id="main">
	<nav id="mobilefilterbrowse">
		<ul class="browse_nav group left">
			<li class="first main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['origin'])? 'All Origins' : '<b>Origin:</b> '. $origins[$current_condition['origin']]; ?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('origin');?>">All</a></li>
							<?php foreach ($origins as $k => $o): ?>
							<li><a href="<?php echo $this->QueryString->add('origin', $k); ?>"><?php echo $o;?></a></li>
							<?php endforeach;?>
							<li><a href="<?php echo $this->QueryString->add('origin', 'other');?>">Other</a></li>
						</ul>
					</div>
				</div>
			</li>
			<li class="main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['dictionary']) ? 
					'All Dictionaries' : '<b>Dictionary:</b> ' . $dictionaries[$current_condition['dictionary']];?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('dictionary'); ?>">All</a></li>
							<?php foreach ($dictionaries as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('dictionary', $k); ?>"><?php echo $o;?></a></li><?php endforeach;?>
							<li><a href="<?php echo $this->QueryString->add('dictionary', 'other');?>">Other</a></li>
						</ul>
					</div>
				</div>
			</li>
			<li class="main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['use']) ? 'All Uses' : '<b>Use:</b> ' . $types[$current_condition['use']];?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('use');?>">All</a></li>
						<?php foreach ($types as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('use', $k); ?>"><?php echo $o;?></a></li><?php endforeach;?>
							<li><a href="<?php echo $this->QueryString->add('use', 'other');?>">Other</a></li>
						</ul>
					</div>
				</div>
			</li>
			<li class="main last">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['region']) ? 'All Regions' : '<b>Region:</b> ' . $regions[$current_condition['region']];?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('region');?>">All</a></li>
						<?php foreach ($regions as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('region', $k); ?>"><?php echo $o;?></a></li><?php endforeach;?>
							<li><a href="<?php echo $this->QueryString->add('region', 'other');?>">Other</a></li>
						</ul>
					</div>
				</div>
			</li>
		</ul>
	</nav>


	<nav id="browse">
		<ul class="browse_nav group left">
			<li class="first main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['origin'])? 'All Origins' : '<b>Origin:</b> '. $origins[$current_condition['origin']]; ?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('origin');?>">All</a></li>
							<?php foreach ($origins as $k => $o): ?>
							<li><a href="<?php echo $this->QueryString->add('origin', $k); ?>"><?php echo $o;?></a></li>
							<?php endforeach;?>
							<li><a href="<?php echo $this->QueryString->add('origin', 'other');?>">Other</a></li>
						</ul>
					</div>
				</div>
			</li>
			<li class="main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['dictionary']) ? 
					'All Dictionaries' : '<b>Dictionary:</b> ' . $dictionaries[$current_condition['dictionary']];?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('dictionary'); ?>">All</a></li>
							<?php foreach ($dictionaries as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('dictionary', $k); ?>"><?php echo $o;?></a></li><?php endforeach;?>
							<li><a href="<?php echo $this->QueryString->add('dictionary', 'other');?>">Other</a></li>
						</ul>
					</div>
				</div>
			</li>
			<li class="main">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['use']) ? 'All Uses' : '<b>Use:</b> ' . $types[$current_condition['use']];?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('use');?>">All</a></li>
						<?php foreach ($types as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('use', $k); ?>"><?php echo $o;?></a></li><?php endforeach;?>
							<li><a href="<?php echo $this->QueryString->add('use', 'other');?>">Other</a></li>
						</ul>
					</div>
				</div>
			</li>
			<li class="main last">
				<div class="dropdown">
					<a class="main">
						<span><?php echo empty($current_condition['region']) ? 'All Regions' : '<b>Region:</b> ' . $regions[$current_condition['region']];?></span>
					</a>
					<div class="submenu">
						<ul>
							<li><a href="<?php echo $this->QueryString->remove('region');?>">All</a></li>
						<?php foreach ($regions as $k => $o): ?><li><a href="<?php echo $this->QueryString->add('region', $k); ?>"><?php echo $o;?></a></li><?php endforeach;?>
							<li><a href="<?php echo $this->QueryString->add('region', 'other');?>">Other</a></li>
						</ul>
					</div>
				</div>
			</li>
		</ul>
	</nav>
	

	<?php if (sizeof($words) == 0):?>
		<div class="c content">
			<p>No words were found. Refine your search options above.</p>
		</div>
	<?php else:?>

		<div id="browse_info" class="group">
			<p id="paging_info">
				<?php echo $this->Paginator->counter('range');?>
			</p>
		</div>
		
		<ul class="word-list">
		<?php foreach ($words as $word): ?>
			<li class="group">
				<div class="word-main">
					<h3><?php echo $this->Html->link($word->spelling, '/words//'.$word->id); ?>
					<?php echo $this->Html->image('seefullentry.jpg', 
												['url' => '/words//'.$word->id, 
												'width' => 111,
												'height' => 20,
												'class' => 'seefullentrybutton'])?></h3>
					<p class="definition"><?php echo sizeof($word->definitions) > 0 ? $word->definitions[0]->definition : '';?></p>
				</div>
			</li>
		<?php endforeach; ?>

		</ul>
			<?php if ($this->Paginator->hasPrev()) :?>
				<?= $this->Paginator->prev(' << ' . __('previous'));?>
			<?php endif ?>
			<?php if ($this->Paginator->hasNext()) :?>
				<?= $this->Paginator->next(' >> ' . __('next'));?>
			<?php endif ?>	

	<?php endif;?>


</section>