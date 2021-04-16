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
<?php echo $this->Html->image('header3.jpg', ['height' => 200, 'width' => 900])?>

 </div>
	
	<div class="page-header group">
		<h2 class="left">Lexicon</h2>
		</div>
    <div id="home2" class="c">
		<div class="internal-home">
		<p id="first_time_here">First time here? Read our <?php echo $this->Html->link('welcome', '/welcome');?> page</a>.</p>
		
		<p class="m3 center">The lexicon currently has <?=$this->Html->link($total_entries, '/words');?> entries, including <br /><?=$this->Html->link($no_dict_entries, '/words?dictionary=none');?> entries that do not appear in any Jewish English dictionary.</p>
		<hr class="m2" />
			<h3 class="m1">BROWSE:</h3>
		<div class="browse">
		<?php echo $this->Html->image('inorder.jpg', 
                                            ['url' => '/alphabetical', 
                                            'width' => 239,
                                            'height' => 60,
											'class' => 'button'])?>
		<?php echo $this->Html->image('random_button.jpg', 
                                            ['url' => '/random', 
                                            'width' => 239,
                                            'height' => 60,
											'class' => 'button'])?>									
    	</div>
        <hr class="m2" />
        <h3>SEARCH:</h3>
		<form id="home_search" class="group m3" type="GET" action="<?php echo $this->Url->build(["controller" => "Search"]);?>">
				<input type="text" placeholder="Search" name="q" />
				<a class="button blue" onclick="document.getElementById('home_search').submit();"><i class="icon-search"></i></a>
			</form>
		<!--<form id="home_search" class="group m3" type="GET" action="<?php echo $this->Html->link('/search', true);?>">
			<input type="text" placeholder="Search" name="q" />
			<a class="button blue" onclick="document.getElementById('home_search').submit();"><i class="icon-search"></i></a>
		</form>-->
        <hr class="m2" />
		<h3 class="m1">ADVANCED SEARCH:</h3>
		<p class="m2">(See the <?php echo $this->Html->link('NOTES', '/notes');?> for information about these languages, dictionaries, and types of people.)</p>

		<ul id="home2_filters">
			<li>
				<h4><i class="icon-comments-alt"></i> Languages of origin</h4>
				<ul>
					<?php foreach ($origins as $id => $o):?>
						<li><?php echo $this->Html->link($o, '/words?origin='.$id);?></li>
					<?php endforeach;?>
					<li><?php echo $this->Html->link('Other', '/words?origin=other');?></li>
				</ul>
			</li>
			<li>
				<h4><i class="icon-globe"></i> Regions in which the word is used</h4>
				<ul>
					<?php foreach ($regions as $id => $o):?>
						<li><?php echo $this->Html->link($o, '/words?region='.$id);?></li>
					<?php endforeach;?>
					<li><?php echo $this->Html->link('Other', '/words?region=other');?></li>
				</ul>
			</li>
			<li>
				<h4><i class="icon-user"></i> Types of people who tend to use the word</h4>
				<ul class="m3">
					<?php foreach ($types as $id => $o):?>
						<li><?php echo $this->Html->link($o, '/words?use='.$id);?></li>
					<?php endforeach;?>
					<li><?php echo $this->Html->link('Other', '/words?use=other');?></li>
				</ul>
			</li>
			<li>
				<h4><i class="icon-book"></i> Dictionaries in which the word appears</h4>
				<ul>
					<?php foreach ($dictionaries as $id => $o):?>
						<li><?php echo $this->Html->link($o, '/words?dictionary='.$id);?></li>
					<?php endforeach;?>
					<li><?php echo $this->Html->link('Other', '/words?dictionary=other');?></li>
					<li><?php echo $this->Html->link('None', '/words?dictionary=none');?></li>
				</ul>
			</li>
		</ul>
		<hr class="m2" />
		<h3>CAN'T FIND A WORD?</h3>
		<p class="m3">Like other collaborative sites, such as Wikipedia and Urban Dictionary, the Jewish English Lexicon is made possible by visitor participation. Please take a few minutes to add a word or two.</p>
		<div class="browse"><?php echo $this->Html->image('add_button.jpg', 
                                            ['url' => '/add', 
                                            'width' => 154,
                                            'height' => 60,
											'class' => 'button'])?>	</div>
		<p>&nbsp;</p>
		</div>
	</div>


</section>