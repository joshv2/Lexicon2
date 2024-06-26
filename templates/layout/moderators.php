<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />

	<title>Jewish English Lexicon - Moderator Panel</title>
	<?php echo $this->Html->css('mod');?>
	<!--<link rel="stylesheet" href="<?=$this->Html->link('/css/mod.css', true);?>">-->
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	
	<![endif]-->
	<script src="https://www.google.com/recaptcha/api.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

</head>
<body>
<!-- Access to Moderator pages is controlled by the CakeDC/Users plugin -->
	<?= $this->Flash->render() ?>
	<div id="wrapper">
		<div id="top">
			<h1>JEL Control Panel</h1>
			<ul>
				<li>Logged in as <?php echo $this->Identity->get('username');?></li>
				<li><?php echo $this->Html->link('Home', '/');?></li>
				<li><?php echo $this->Html->link('Log out', '/logout');?></li>
				
			</ul>
			<div class="clear"></div>
		</div>
		<div id="left">
			<div id="left_inner">
				<ul class="nav">
					<li><?php echo $this->Html->link('Moderator Panel', '/moderators');?></li>
					<li><?php echo $this->AuthLink->link('Add User', '/Users/add');?></li>
					<li><?php echo $this->AuthLink->link('Add Language', '/Languages/add');?></li>
					<li><?php echo $this->AuthLink->link('Edit Current Language', '/Languages/edit/' . $sitelang->id);?></li>
					<li><?php echo $this->AuthLink->link('Log', '/moderators/logs');?></li>
					<li><?php echo $this->AuthLink->link('My Contributions', '/moderators/me');?></li>
					<li><?php echo $this->Html->link('Add a New Word', '/add');?></li>
					<li><?php echo $this->Html->link('Change Password', '/Users/change-password/' . $userid);?></li>
					<?php if ('superuser' == $userLevel):?>
					<li><?php echo 'There are ' . $remainingcredits . ' remaining MP3 conversion credits'; ?></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
		<div id="right">
			<div id="right_inner">
				
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
		<div class="clear"></div>

		<?php echo $this->fetch('js_bottom'); ?>

		<?php //echo $this->element('sql_dump'); ?>
	</div>


</body>
</html>
