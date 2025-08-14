<section id="main">
	<div id="browse_info">
	<?php
	function renderResultSummary($q, $countVal, $originSummary, $addMargin = false) {
	    $resultWord = ($countVal === 1) ? __('result') : __('results');
	    $style = $addMargin ? ' style="display:inline-block; margin-right: 1.5em;"' : '';
	    echo '<span class="m0"' . $style . '>';
	    echo __('Your search for') . ' <b>' . h($q) . '</b> ' . __('returned') . ' ' . $countVal . ' ' . $resultWord;
	    if (!empty($originSummary)) {
	        echo ', ' . __('of which') . ' ';
	        if (preg_match_all('/(\d+) were from ([^,]+)/', $originSummary, $matches, PREG_SET_ORDER)) {
	            $parts = [];
	            foreach ($matches as $m) {
	                $verb = ($m[1] == 1) ? __('was') : __('were');
	                $parts[] = $m[1] . ' ' . $verb . ' from ' . $m[2];
	            }
	            if (count($parts) > 1) {
	                $last = array_pop($parts);
	                echo implode(', ', $parts) . ', and ' . $last;
	            } else {
	                echo $parts[0];
	            }
	        } else {
	            echo $originSummary;
	        }
	    }
	    echo '.';
	    echo '</span>';
	}
	if ($isPaginated) {
	    $countVal = (int)$this->Paginator->counter('{{count}}');
	} else {
	    $countVal = (int)$count;
	}
	?>
	<div class="line-container">
		<?php renderResultSummary($q, $countVal, $originSummary, $isPaginated); ?>
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
	<?php if ($isPaginated && $this->Paginator->counter('{{count}}') <= 0): ?>	

		<div class="c content">
			<p><?= __('That word is not yet in the database. Try searching with a different spelling.');?></p>
			<p><?= __("If you still don't find it, please help to make this lexicon more complete by adding it");?>:&nbsp;&nbsp;&nbsp;&nbsp;<?=$this->Html->link('<i class="fa-solid fa-plus"></i> ' . __('Add a new word'), '/add',
												['class' => 'button blue', 'escape' => false]);?></p>
		</div>
	<?php else: ?>
		<ol class="word-list group">
		<?php $i = 1;
		foreach ($words as $word): ?>
			<li class="group">
				<div class="num"><?php echo $i;?></div>
				<div class="word-main">
					<h3><?php echo $this->Html->link($word->spelling, '/words//'.$word->id); ?></h3>
					<?php echo $this->Html->link(__('SEE FULL ENTRY') . ' <i class="fa fa-caret-down"></i>', '/words//'.$word->id, ['class' => 'noborder', 'escape' => false]); ?>
					
				</div>
			</li>
		<?php ++$i; endforeach; ?>
		</ol>
		<?php if ($isPaginated): ?>
		<div class="pagination">
			<?php if ($this->Paginator->hasPrev()) :?>
					<?= $this->Paginator->prev(' << ' . __('previous'));?>
				<?php endif ?>
				<?php if ($this->Paginator->hasNext()) :?>
					<?= $this->Paginator->next(' >> ' . __('next'));?>
				<?php endif ?>	
			<div class="clear"></div>
		</div>
		<?php endif; ?>

	<?php endif;?>
</section>
