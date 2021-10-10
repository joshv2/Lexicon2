<?php echo $this->Html->script('nav', array('block' => 'js_bottom'));?>

<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
	<ul class="right">
		<li>
		<?=$this->Html->link(__('<i class="icon-plus-sign"></i> Add a new word'), '/add',
										['class' => 'button blue', 'escape' => false]);?>
		</li>
	</ul>
</nav>

<section id="main">
	<nav id="mobilealphabrowse">
		<div id="alphabetical_list">
			<h3 id="alphabrowseheader"><?=__("Browse by Letter")?></h3>
			<ul class="pagination group">
			<?php
			for ($i = 65; $i < 91; $i++)
			{
				$uc = chr($i);
				$lc = chr($i + 32);
				if ($letter == $uc || $letter == $lc)
					echo '<li class="current">'.$uc.'</li>';
				else
					echo '<li>'. 
					$this->Html->link($uc, '/alphabetical//'. $lc) . '</li>';
			}
			?>
			</ul>
		</div>
	</nav>
	
	<nav id="browse">
		<div id="alphabetical_list">
			
			<ul class="pagination group">
			<?php
			for ($i = 65; $i < 91; $i++)
			{
				$uc = chr($i);
				$lc = chr($i + 32);
				if ($letter == $uc || $letter == $lc)
					echo '<li class="current">'.$uc.'</li>';
				else
				echo '<li>'. 
				$this->Html->link($uc, '/alphabetical//'. $lc) . '</li>';
			}
			?>
			</ul>
		</div>
	</nav>

	<div id="browse_info" class="group">
		<p id="sort_info"><?php echo $letter;?></p>
		<p id="paging_info">
			<?=__("Showing ")?><b><?php echo count($words);?></b> <?=__("words")?>
		</p>
	</div>

	<?php if (sizeof($words) == 0):?>
	<div class="c content">
		<p><?=__("No words were found.")?></p>
	</div>
	<?php else:?>

	<ul class="word-list">
	<?php foreach ($words as $word): ?>
		<li class="group">
			<div class="word-main">
				<h3>
				
				<?php echo $this->Html->link(__($word->spelling, '/words//'.$word->id));?>
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

	<div class="pagination">
	<ul class="pagination group">
		<?php
		for ($i = 65; $i < 91; $i++)
		{
			$uc = chr($i);
			$lc = chr($i + 32);
			if ($letter == $uc || $letter == $lc)
				echo '<li class="current">'.$uc.'</li>';
			else
				echo '<li>'. $this->Html->link(__($uc, '/alphabetical//'. $lc)) . '</li>';
		}
		?>
	</ul>
	<div class="clear"></div>
	</div>
	<?php endif;?>
</section>
