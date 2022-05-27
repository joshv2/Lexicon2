<?php $this->start('js_bottom');?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#home2_filters li h4').click(function() {
		$('#home2_filters li h4').removeClass('on');
	 	$('#home2_filters li ul').slideUp('normal');
		if($(this).next().is(':hidden') == true) {
			$(this).addClass('on');
			$(this).next().slideDown('normal');
		 }
	 });
	$('#home2_filters li ul').hide();

});
</script>
<?php $this->end();?>
<!--<nav id="crumbs" class="group">
	<?php //echo $this->element('user_bar');?>
	<ul class="right">
	</ul>
</nav>-->

<section id="main">
<div id="header-image">
<?php echo $this->Html->image($sitelang->HeaderImage, ['height' => 200, 'width' => 900])?>

 </div>
	
	<div class="page-header group">
		<h2 class="left">Lexicon</h2>
		</div>
    <div id="home2" class="c">
		<div class="internal-home">
		<p id="first_time_here"><?=__("First time here? Read our ")?><?php echo $this->Html->link(__('welcome '), '/about');?><?=__("page")?></a>.</p>
		
		<p class="m3 center"><?=__("The lexicon currently has ")?><?php echo ($total_entries > 0) ? $this->Html->link(__($total_entries), '/words') : '0';?> 
		<?php if($sitelang->hasDictionaries): ?>
			<?=__("entries, including ")?><br /><?=$this->Html->link(__($no_dict_entries), '/words?dictionary=none');?> <?=__("entries that do not appear in any Jewish English dictionary.")?></p>
		<?php else: ?>
			<?=__("entries.") ?>
		<?php endif; ?>
		<hr class="m2" />
			<h3 class="m1"><?=__("BROWSE:")?></h3>
		<div class="browse">
		<p>
		<p id="enterthelexicon"><?php echo	$this->Html->link(__('See full lexicon in alphabetical order'), '/alphabetical', ["class" => "button blue"]);?></p>
		<p id="enterthelexicon"><?php echo	$this->Html->link(__('See a random list of words'), '/alphabetical', ["class" => "button blue"]);?></p>
		</p>
		<!-- Deprecated 5/27 - <?php echo $this->Html->image('inorder.jpg', 
                                            ['url' => '/alphabetical', 
                                            'width' => 239,
                                            'height' => 60,
											'class' => 'button'])?>
		<?php echo $this->Html->image('random_button.jpg', 
                                            ['url' => '/random', 
                                            'width' => 239,
                                            'height' => 60,
											'class' => 'button'])?>	-->								
    	</div>
        <hr class="m2" />
        <h3><?=__("SEARCH:")?></h3>
		<form id="home_search" class="group m3" type="GET" action="/search">
				<?php echo '<input type="text" placeholder="' . __('Search') . '" name="q" />'; ?>
				<a class="button blue" id="homepagesearch" onclick="document.getElementById('home_search').submit();"><i class="icon-search"></i></a>
			</form>

        <hr class="m2" />
		<h3 class="m1"><?=__("ADVANCED SEARCH:")?></h3>
		<p class="m2">(<?=__("See the") ?> <?php echo $this->Html->link(__('NOTES'), '/notes');?> <?=__("for information about these languages, dictionaries, and types of people.")?>)</p>

		<ul id="home2_filters">
		<?php if($sitelang->hasOrigins): ?>
			<li>
				<h4><i class="icon-comments-alt"></i> <?=__("Languages of origin")?></h4>
				<ul>
				<?php foreach ($origins as &$neworigin){
                        $neworigin = __($neworigin);
                    } ?>
					<?php foreach ($origins as $id => $o):?>
						<li><?php echo $this->Html->link($o, '/words?origin='.$id);?></li>
					<?php endforeach;?>
					<li><?php echo $this->Html->link(__('Other'), '/words?origin=other');?></li>
				</ul>
			</li>
		<?php endif; ?>
		<?php if($sitelang->hasRegions): ?>
			<li>
				<h4><i class="icon-globe"></i> <?=__("Regions in which the word is used")?></h4>
				<ul>
					
				<?php foreach ($regions as &$newregion){
                        $newregion = __($newregion);
                    } ?>
				<?php foreach ($regions as $id => $o):?>
						<li><?php echo $this->Html->link($o, '/words?region='.$id);?></li>
					<?php endforeach;?>
					<li><?php echo $this->Html->link(__('Other'), '/words?region=other');?></li>
				</ul>
			</li>
		<?php endif; ?>
		<?php if($sitelang->hasTypes): ?>
			<li>
				<h4><i class="icon-user"></i> <?=__("Types of people who tend to use the word")?></h4>
				<ul class="m3">
					<?php foreach ($types as &$newtype){
                        $newtype = __($newtype);
                    } ?>
					<?php foreach ($types as $id => $o):?>
						<li><?php echo $this->Html->link($o, '/words?use='.$id);?></li>
					<?php endforeach;?>
					<li><?php echo $this->Html->link(__('Other'), '/words?use=other');?></li>
				</ul>
			</li>
		<?php endif; ?>
		<?php if($sitelang->hasDictionaries): ?>
			<li>
				<h4><i class="icon-book"></i> <?=__("Dictionaries in which the word appears")?></h4>
				<ul>
					<?php foreach ($dictionaries as $id => $o):?>
						<li><?php echo $this->Html->link($o, '/words?dictionary='.$id);?></li>
					<?php endforeach;?>
					<li><?php echo $this->Html->link(__('None'), '/words?dictionary=none');?></li>
				</ul>
			</li>
		<?php endif; ?>
		</ul>
		<hr class="m2" />
		<h3><?=__("CAN'T FIND A WORD?")?></h3>
		<p class="m3"><?=__("Like other collaborative sites, such as Wikipedia and Urban Dictionary, the Jewish English Lexicon is made possible by visitor participation. Please take a few minutes to add a word or two.")?></p>
		<div class="browse"><?php echo $this->Html->image('add_button.jpg', 
                                            ['url' => '/add', 
                                            'width' => 154,
                                            'height' => 60,
											'class' => 'button'])?>	</div>
		<p>&nbsp;</p>
		</div>
	</div>


</section>