<nav id="crumbs" class="group">
</nav>

<section id="main">
	<div class="page-header group">
		<h2 class="left"><?php echo $word['Word']['spelling'];?> <small>- Editing</small></h2>
	</div>

	<div class="c word">
	<h2>SUGGEST CHANGES FOR <?php echo $word['Word']['spelling'];?></h2>
			<p class="notes">You are about to enter suggested edits for this entry. In case you have questions about languages of origin, spelling, or who uses it, please see <a href=" http://www.jewish-languages.org/jewish-english-lexicon/notes" target="_blank">Notes.</a> <br /><br />What edits would you like to suggest to this entry? Please be specific.</p>
<?php
echo $this->Form->create(false, array(
    'url' => array('controller' => 'add', 'action' => 'edit')));
	echo $this->Form->textarea('changenotes');
	echo $this->Form->input('name', array('type' => 'text', 
		'name' => 'data[Name]'));
	echo $this->Form->input('email', array('type' => 'email'));
	echo '<input type="hidden" name="data[id]" value="' . $word['Word']['id'] . '">';
	echo '<div class="g-recaptcha" data-sitekey="6LdXhXwUAAAAAE6bcodYGt-FgNvlUJdcme3WprFh"></div>';
	echo $this->Form->button('Submit', array('type' => 'submit'));
echo $this->Form->end(); 
?>
</section>