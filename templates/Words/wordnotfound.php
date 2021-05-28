<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
</nav>
<section id="main">
    <div class="page-header group">
		<h2 class="left">Error</h2>
	</div>
	<div class="c">
		<p>This word can not be found or has not yet been approved. 
		<?php 
			echo "Add a " . $this->Html->link('word', ['controller' => 'Words', 'action' => 'add']) . ".";

			?>
		</p>
	</div>
	
</section>
