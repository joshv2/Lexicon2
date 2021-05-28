<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
</nav>
<section id="main">
    <div class="page-header group">
		<h2 class="left">Thank you</h2>
	</div>
	<div class="c">
		<p>Thank you for adding an entry to the Jewish English Lexicon. Check back in about a week to see your entry on the website.
		<?php 
			if (isset($this->request->getParam('pass')[0])) {
				echo "See your " . $this->Html->link('entry', ['controller' => 'Words', 'action' => 'view', $this->request->getParam('pass')[0]]) . ".";
			}
			?>
		</p>
	</div>
	
</section>
