<?php echo $this->Html->script('nav', array('block' => 'js_bottom'));?>
<!--<section id="main">-->
	
	<div class="alphabg">
	<div class="centeralpha">
	<nav class="pagination">
		<!--<div id="alphabetical_list">-->
			
			<ul class="browsealpha">
			<?php
			foreach($alphabet as $k => $v){
				if ($letter == $v){
					echo '<li class="current">'.$v.'</li>';
				} else {
					echo '<li>'. 
					$this->Html->link($v, '/alphabetical//'. $v) . '</li>';
				}
			}
			/*for ($i = 65; $i < 91; $i++)
			{
				$uc = chr($i);
				$lc = chr($i + 32);
				if ($letter == $uc || $letter == $lc)
					echo '<li class="current">'.$uc.'</li>';
				else
				echo '<li>'. 
				$this->Html->link($uc, '/alphabetical//'. $lc) . '</li>';
			}*/
			?>
			</ul>
		<!--</div>-->
	</nav>
	</div>
	</div>
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
					<h3><?php echo $this->Html->link($word->spelling, '/words//'.$word->id); ?></h3>
					<?php echo $this->Html->link(__('SEE FULL ENTRY') . ' <i class="fa fa-caret-down"></i>', '/words//'.$word->id, ['class' => 'noborder', 'escape' => false]); ?>
					
				</div>
		</li>
	<?php endforeach; ?>
	</ul>

	<div class="alphabg">
	<div class="centeralpha">
	<div class="pagination">
	<ul class="browsealpha">
		<?php
		foreach($alphabet as $k => $v){
			if ($letter == $v){
				echo '<li class="current">'.$v.'</li>';
			} else {
				echo '<li>'. 
				$this->Html->link($v, '/alphabetical//'. $v) . '</li>';
			}
		}
		/*for ($i = 65; $i < 91; $i++)
		{
			$uc = chr($i);
			$lc = chr($i + 32);
			if ($letter == $uc || $letter == $lc)
				echo '<li class="current">'.$uc.'</li>';
			else
				echo '<li>'. $this->Html->link(__($uc, '/alphabetical//'. $lc)) . '</li>';
		}*/
		?>
	</ul>
	<div class="clear"></div>
	</div>
	</div>
	</div>
	<?php endif;?>
<!--</section>-->
