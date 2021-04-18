<?php echo $this->Html->script('nav', array('block' => 'js_bottom'));?>

<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
	<ul class="right">
		<li><a class="button blue" href="<?php echo $this->Html->url('/add', true);?>"><i class="icon-plus-sign"></i> Add a new word</a></li>
	</ul>
</nav>

<section id="main">
	<nav id="mobilealphabrowse">
		<div id="alphabetical_list">
			<h3 id="alphabrowseheader">Browse by Letter</h3>
			<ul class="pagination group">
			<?php
			for ($i = 65; $i < 91; $i++)
			{
				$uc = chr($i);
				$lc = chr($i + 32);
				if ($letter == $uc || $letter == $lc)
					echo '<li class="current">'.$uc.'</li>';
				else
					echo '<li><a href="'.$this->Html->url("/alphabetical/$lc", true).'">'.$uc.'</a></li>';
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
					echo '<li><a href="'.$this->Html->url("/alphabetical/$lc", true).'">'.$uc.'</a></li>';
			}
			?>
			</ul>
		</div>
	</nav>

	<div id="browse_info" class="group">
		<p id="sort_info"><?php echo $letter;?></p>
		<p id="paging_info">
			Showing <b><?php echo count($words);?></b> words
		</p>
	</div>

	<?php if (sizeof($words) == 0):?>
	<div class="c content">
		<p>No words were found.</p>
	</div>
	<?php else:?>

	<ul class="word-list">
	<?php foreach ($words as $word): ?>
		<li class="group">
			<div class="word-main">
				<h3><?php echo $this->Html->link($word['Word']['spelling'], '/words/'.$word['Word']['id']); ?>
					<?php /*?><small><a href="<?=$this->Html->url('/words/'.$word['Word']['id']);?>">See full entry</a></small><?php */?>
                    <a href="<?php echo $this->Html->url('/words/'.$word['Word']['id']);?>"> <img class="seefullentrybutton" src="<?php echo$this->Html->url('/seefullentry.jpg', true);?>" height="20" width="111" /></a>
                    </h3>
				<?php foreach ($word['Definition'] as $d): ?>
					<p class="definition"><?php echo h($d['definition']);?></p>
				<?php endforeach;?>
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
				echo '<li><a href="'.$this->Html->url("/alphabetical/$lc", true).'">'.$uc.'</a></li>';
		}
		?>
	</ul>
	<div class="clear"></div>
	</div>
	<?php endif;?>
</section>
