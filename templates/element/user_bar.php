
	<?php //$loggedIn = false;
	if ($this->Identity->isLoggedIn()):?>
	<ul class="left">
		<li>
		<?=$this->Html->link('Log out ' . $this->Identity->get('username'), '/moderators/logout',
										['class' => 'button grey']);?>
		</li>
		<li>
		<?=$this->Html->link('Admin Panel', '/moderators',
										['class' => 'button grey']);?></li>
	</ul>
	<?php endif;?>
	
