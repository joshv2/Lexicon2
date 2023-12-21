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

<!--<section id="main">-->
<div id="header-image">
<?php if ('' != $sitelang->HeaderImage):?>
<?php echo $this->Html->image($sitelang->HeaderImage, ['height' => 200, 'width' => '100%'])?>
<?php endif; ?>
 </div>
	
	<div class="page-header group">
		<h2 class="left"><?php echo __('Lexicon'); ?></h2>
		</div>
    <div id="home2" class="c">
	
		<div class="internal-home" id="upperhomesearch">
			<form id="home_search" class="group m3" type="GET" action="/search">
				<?php echo '<input type="text" placeholder="' . __('Search') . '" name="q" />'; ?>
				<a class="button blue" id="homepagesearch" onclick="document.getElementById('home_search').submit();"><i class="icon-search"></i></a>
			</form>
			<hr class="m2" />
		</div>

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
		<p id="enterthelexicon"><?php echo	$this->Html->link(__('See a random list of words'), '/random', ["class" => "button blue"]);?></p>
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
        <div id="lowerhomesearch">
			<!--<h3><?=__("SEARCH:")?></h3>-->
			<form id="home_search" class="group m3" type="GET" action="/search">
				<?php echo '<input id="btmsearch" type="text" placeholder="' . __('Search') . '" name="q" />'; ?>
				<a class="button blue" id="homepagesearch" onclick="document.getElementById('home_search').submit();"><i class="icon-search"></i></a>
			</form>
			<hr class="m2" />
		</div>
        
		<h3 class="m1"><?=__("ADVANCED SEARCH:")?></h3>
		<p class="m2">(<?=__("See the") ?> <?php echo $this->Html->link(__('NOTES'), '/notes');?> <?=__("for information about these languages, dictionaries, and types of people.")?>)</p>

		<ul id="home2_filters">
		<?php if($sitelang->hasOrigins): ?>
			<details class="home2_details">
				<summary class="home2_summary">
					<i class="icon-comments-alt"></i> <?=__("Languages of origin")?>
				</summary>
				<ul class="adv_search_ul">
				<?php foreach ($origins as &$neworigin){
                        $neworigin = __($neworigin);
                    } ?>
					<?php foreach ($origins as $id => $o):?>
						<li><?php echo $this->Html->link($o, '/words?origin='.$id);?></li>
					<?php endforeach;?>
					<li><?php echo $this->Html->link(__('Other'), '/words?origin=other');?></li>
				</ul>
			</details>
		<?php endif; ?>
		<?php if($sitelang->hasRegions): ?>
			<details class="home2_details">
				<summary class="home2_summary">
					<i class="icon-globe"></i> <?=__("Regions in which the word is used")?>
				</summary>
				<ul class="adv_search_ul">
					
				<?php foreach ($regions as &$newregion){
                        $newregion = __($newregion);
                    } ?>
				<?php foreach ($regions as $id => $o):?>
						<li><?php echo $this->Html->link($o, '/words?region='.$id);?></li>
					<?php endforeach;?>
					<li><?php echo $this->Html->link(__('Other'), '/words?region=other');?></li>
				</ul>
			</details>
		<?php endif; ?>
		<?php if($sitelang->hasTypes): ?>
			<details class="home2_details">
				<summary class="home2_summary">
					<i class="icon-user"></i> <?=__("Types of people who tend to use the word")?>
				</summary>
				<ul class="adv_search_ul">
				<?php 
				$otherarray = [];
				foreach ($types as $type) {
					
					if (isset($type->category)){
						echo __($type->category) . "<br>";
					}
					if (is_array($type->types)) {
						foreach ($type->types as $subtype) {
							// If it's a subarray, list all its values in the first index
							//$output = implode(", ", $type);
							if (str_contains($subtype->type, ":")) {
								$boldMainName = explode(":", $subtype->type);
								$boldMainName[0] = $boldMainName[0] . ":";
							} else {
								$boldMainName = [$subtype->type, ""];
							}
							echo "<li>". $this->Html->link("<span class='boldname'>" . __($boldMainName[0]) . "</span>"
								. __($boldMainName[1]), '/words?use='.$subtype->id, ['escape' => false]) . "</li>";
						}
						echo "<br>";
					} else {
						// If it's not a subarray, simply print the value
							array_push($otherarray, $type);
					}
					//echo "<br>";
				} 
				if (count($otherarray) > 0) {
					if($sitelang->hasTypeCategories) {
						echo __("Other") . "<br>";
					}
						foreach ($otherarray as $othertype) {
							echo "<li>".  $this->Html->link(__($othertype->type), '/words?use='.$othertype->id) . "</li>";
						}
					}
				?>

				</ul>
			</details>
		<?php endif; ?>
		<?php if($sitelang->hasDictionaries): ?>
			<details class="home2_details">
				<summary class="home2_summary">
					<i class="icon-book"></i> <?=__("Dictionaries in which the word appears")?>
				</summary>
				<ul class="adv_search_ul">
					<?php foreach ($dictionaries as $id => $o):?>
						<li><?php echo $this->Html->link($o, '/words?dictionary='.$id);?></li>
					<?php endforeach;?>
					<li><?php echo $this->Html->link(__('None'), '/words?dictionary=none');?></li>
				</ul>
			</details>
		<?php endif; ?>
		</ul>
		<hr class="m2" />
		<h3><?=__("CAN'T FIND A WORD?")?></h3>
		<p class="m3"><?=__("Like other collaborative sites, such as Wikipedia and Urban Dictionary, the Jewish English Lexicon is made possible by visitor participation. Please take a few minutes to add a word or two.")?></p>
		<div class="browse"><p id="enterthelexicon"><?php echo $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> ' . __('Add an entry'), '/add', ["class" => "button blue", 'escape' => false]);?></p>	</div>
		<p>&nbsp;</p>
		</div>
	</div>


<!--</section>-->