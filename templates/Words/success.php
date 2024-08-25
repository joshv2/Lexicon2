<section id="main">
    <div class="page-header group">
		<h2 class="left"><?=__("Thank you")?></h2>
	</div>
	<div class="c">
		<p><?=__("Thank you for adding an entry to the Jewish English Lexicon. Check back in about a week to see your entry on the website.")?>
		<?php 
			if (isset($this->request->getParam('pass')[0])) {
				echo $this->Html->link(__('See your entry'), ['controller' => 'Words', 'action' => 'view', $this->request->getParam('pass')[0]]) . ".";
			}
			?>
		</p>
	</div>
	
</section>
