<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
	<?= $this->Html->charset() ?>
	<meta name="viewport" content="width=device-width" />
	<?php echo $this->Html->meta([
		'rel' => 'preload',
		'href' => $this->Url->assetUrl(
			'/fonts/fontawesome-webfont.woff2'
		),
		'as' => 'font']
	);?>
	<script src="https://kit.fontawesome.com/3f405633a9.js" crossorigin="anonymous"></script>
	<?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
	<!-- Include stylesheet -->
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <?php echo $this->Html->css('style');?>
	<title><?php echo (isset($title)) ? $title . '- Jewish English Lexicon' : 'Jewish English Lexicon'; ?></title>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-71563013-2"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-71563013-2');
	</script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
	<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>-->
	<?= $this->Html->script('addform')."\n";?>
	<?= $this->Html->script('audioplayback
	')."\n";?>
	<?= $this->Html->script('exConfirm')."\n";?>
<?= $this->Html->script('recorder')."\n";?>
<?= $this->Html->script('toggle')."\n";?>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>

</head>
<body>
<div id="container">
	<header>
		<div class="headergroup">
			<h1>
            <?= $this->cell('Logo'); ?></h1>
			<nav>
				<ul id="navbar_sub">
					<li><?php echo $this->Html->link('Home', '/');?></li>
					<li><?php echo $this->Html->link('Add a Word', '/add');?></li>
					<li><?php echo $this->Html->link('About Us', '/about');?></li>
					<li><?php echo $this->Html->link('Notes', '/notes');?></li>
					<!--<li><a href="http://jewishlexicon.weebly.com" target="_blank">Forum</a></li>-->
					<li><a href="http://www.jewish-languages.org" target="_blank">Jewish Languages</a></li>
					<li><a href="https://www.jewishlanguages.org/lexicon-recording">Volunteer</a></li>
					<li class="last"><a href="https://www.givecampus.com/schools/HebrewUnionCollegeJewishInstituteofReligion/help-newcomers-pronounce-jewish-words">Donate</a></li>
				</ul>
			</nav>
			<form type="GET" action="/search">
				<input type="text" results="5" placeholder="Search..." name="q" id="search">
			</form>
		</div>
		<div class="mobile-header">
		<div id="headerlogo">
			<h1>
			<?php echo $this->Html->image('logo.jpg', 
                                            ['url' => '/', 
                                            'width' => 58,
                                            'height' => 40])?>
			</h1>
		</div>
		<div id="searchform">
			<form type="GET" action="/search">
				<input type="text" results="5" placeholder="Search..." name="q" id="search" />
			</form>
		</div>
		<div id="navigation">
			<div class="topnav" id="myTopnav">
					<?php echo $this->Html->link('Home', '/');?>
					<?php echo $this->Html->link('Add a Word', '/add');?>
					<?php echo $this->Html->link('About Us', '/about');?>
					<?php echo $this->Html->link('Notes', '/notes');?>
					<!--<li><a href="http://jewishlexicon.weebly.com" target="_blank">Forum</a></li>-->
					<a href="http://www.jewish-languages.org" target="_blank">Jewish Languages</a>
					<a href="https://www.jewishlanguages.org/lexicon-recording">Volunteer</a>
					<a href="https://www.givecampus.com/schools/HebrewUnionCollegeJewishInstituteofReligion/help-newcomers-pronounce-jewish-words">Donate</a>
			  <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
			</div>
		</div>
		
		</div> <!--mobile header -->
		
	</header>
	<section id="main">
	<!--<div id="frdiv">
	<p id="fundraiser">The Jewish Language Project is proud to announce that we are adding pronunciation recordings to all entries, based on the requests of many visitors. We invite you to support this important initiative by donating funds, volunteering to record entries, and sharing the crowdfunding campaign:
</br><a id="frlink" href="https://www.givecampus.com/schools/HebrewUnionCollegeJewishInstituteofReligion/help-newcomers-pronounce-jewish-words">Help Newcomers Pronounce Jewish Words!</a></br>
If we meet our fundraising goal by May 3, you can expect to see - and hear! - the pronunciations on this site by late June.</p>
	</div>-->
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
	</div>
	<div class="cfooter"></div>
</div>


	<footer>
		<div id="footer_inner">
			<ul>
				<li><?php echo $this->Html->link('About', '/about');?></li>
				<li><?php echo $this->Html->link('Notes', '/notes');?></li>
				<li><a href="http://jewishlexicon.weebly.com" target="_blank">JEL Forum</a></li>
				<li><a href="http://jewish-languages.org" target="_blank">Jewish-Languages.org</a></li>
				<li><a href="https://www.jewishlanguages.org/lexicon-recording">Volunteer</a></li>
				<li><a href="https://www.givecampus.com/schools/HebrewUnionCollegeJewishInstituteofReligion/help-newcomers-pronounce-jewish-words">Donate</a></li>
			</ul>
			<ul>
				<li class="first"><?php echo $this->Html->link('The Lexicon', '/');?></li>
				<li><?php echo $this->Html->link('Home', '/');?></li>
				<li><?php echo $this->Html->link('Alphabetical', '/alphabetical');?></li>
				<li><?php echo $this->Html->link('Random', '/random');?></li>
				<li><?php echo $this->Html->link('Add a New Word', '/add');?></li>
			</ul>
			<div class="right">
				<p>Jewish English Lexicon - (C) 2012-present, Sarah Bunin Benor, shared with a <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank">Creative Commons Attribution 4.0 International License.</a></p>
			</div>
			<div class="clear"></div>
		</div>
	</footer>
	<?php echo $this->fetch('js_bottom'); ?>
	<!--<?php //echo $this->element('sql_dump'); ?>-->
	<script>
		function myFunction() {
		  var x = document.getElementById("myTopnav");
		  if (x.className === "topnav") {
			x.className += " responsive";
		  } else {
			x.className = "topnav";
		  }
		}
	</script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2080909425204614"
     crossorigin="anonymous"></script>
</body>


</html>
