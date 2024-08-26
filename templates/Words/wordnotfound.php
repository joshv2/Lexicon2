<section id="main">
    <div class="page-header group">
		<h2 class="left"><?= __('Error')?></h2>
	</div>
	<div class="c">
		<p><?=__("This word can not be found or has not yet been approved")?>. 
		<?php 
			echo $this->Html->link(__('Add a word'), ['controller' => 'Words', 'action' => 'add']) . ".";

			?>
		</p>
	</div>
	
</section>
