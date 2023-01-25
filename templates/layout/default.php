<!DOCTYPE html>
<html lang="<?= $sitelang->i18nspec ?>">
<head>
	<?= $this->Html->charset() ?>
	<meta name="viewport" content="width=device-width" />
	<link rel="icon" 
      type="image/ico" 
      href="/img/<?= $sitelang->i18nspec ?>/favicon.ico" />
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2080909425204614"
     crossorigin="anonymous"></script>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({
		google_ad_client: "ca-pub-2080909425204614",
		enable_page_level_ads: true,
		overlays: {bottom: true}
	});
	</script>
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
	<title><?php echo (isset($title)) ? $title . ' - ' . $sitelang->name : $sitelang->name; ?></title>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $sitelang->googleAnalytics ?>"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', '<?= $sitelang->googleAnalytics ?>');
	</script>
	<?php if ('' != $sitelang->googleAnalyticsOld):?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $sitelang->googleAnalyticsOld ?>"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', '<?= $sitelang->googleAnalyticsOld ?>');
	</script>
	<?php endif; ?>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
	<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>-->
	<?= $this->Html->script('addform')."\n";?>
	<?= $this->Html->script('audioplayback')."\n";?>
	<?= $this->Html->script('exConfirm')."\n";?>
<?= $this->Html->script('recorder')."\n";?>
<?= $this->Html->script('toggle')."\n";?>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<?= $this->Html->script('detectios')."\n";?>
<script src='https://www.google.com/recaptcha/api.js'></script>

</head>
<body>
<div id="container">
	<header>
	<div id="desktop">	
		<div class="headergroup">
		<div class="flex"> 	<span>
		<h1>
            <?= $this->cell('Logo'); ?></h1></span></div>
			<nav class="flex"> <span>
			<div id="navbar_subdiv">
			<ul id="navbar_sub">
					<li><?php echo $this->Html->link(__('Home'), '/');?></li>
					<li><?php echo $this->Html->link(__('Add a Word'), '/add');?></li>
					<li><?php echo $this->Html->link(__('About Us'), '/about');?></li>
					<li><?php echo $this->Html->link(__('Notes'), '/notes');?></li>
					<!--<li><a href="http://jewishlexicon.weebly.com" target="_blank">Forum</a></li>-->
					<li><a href="http://www.jewish-languages.org" target="_blank"><?= __('Jewish Languages') ?></a></li>
					<li><a href="https://www.jewishlanguages.org/lexicon-recording"><?= __('Volunteer') ?></a></li>
					<li class="last"><a href="https://www.givecampus.com/schools/HebrewUnionCollegeJewishInstituteofReligion/help-newcomers-pronounce-jewish-words"><?= __('Donate') ?></a></li>
				</ul><div></span>
			</nav>
			<div class="flex"><span>
			<form type="GET" action="/search">
				<?php echo '<input type="text" results="5" placeholder="' . __('Search...') . '" name="q" id="search">'; ?>
			</form></span>
			</div>
		</div>
	</div>
		<div class="mobile-header">
		<div id="headerlogo">
			<h1>
			<?= $this->cell('Logo'); ?></h1>
			</h1>
		</div>
		<div id="searchform">
			<form type="GET" action="/search">
			<?php echo '<input type="text" results="5" placeholder="' . __('Search...') . '" name="q" id="search">'; ?>
			</form>
		</div>
		<div id="navigation">
			<div class="topnav" id="myTopnav">
			<?php echo $this->Html->link(__('Home'), '/');?>
					<?php echo $this->Html->link(__('Add a Word'), '/add');?>
					<?php echo $this->Html->link(__('About Us'), '/about');?>
					<?php echo $this->Html->link(__('Notes'), '/notes');?>
					<!--<li><a href="http://jewishlexicon.weebly.com" target="_blank">Forum</a></li>-->
					<a href="http://www.jewish-languages.org" target="_blank"><?= __('Jewish Languages') ?></a>
					<a href="https://www.jewishlanguages.org/donate"><?= __('Volunteer') ?></a>
					<a href="https://www.jewishlanguages.org/donate"><?= __('Donate') ?></a>
			  <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
			</div>
		</div>
		
		</div> <!--mobile header -->
		
	</header>
	<section id="main">
	
	<?php echo $this->element('user_bar');?>

	<!--<div id="frdiv">
	<p id="fundraiser">The Jewish Language Project is proud to announce that we are adding pronunciation recordings to all entries, based on the requests of many visitors. We invite you to support this important initiative by donating funds, volunteering to record entries, and sharing the crowdfunding campaign:
</br><a id="frlink" href="https://www.givecampus.com/schools/HebrewUnionCollegeJewishInstituteofReligion/help-newcomers-pronounce-jewish-words">Help Newcomers Pronounce Jewish Words!</a></br>
If we meet our fundraising goal by May 3, you can expect to see - and hear! - the pronunciations on this site by late June.</p>
	</div>-->
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
	
	</div>
	<!--<div class="cfooter"></div>-->
</div>


	<footer>
		<div id="footer_inner">
			<ul>
				<li><?php echo $this->Html->link(__('About Us'), '/about');?></li>
				<li><?php echo $this->Html->link(__('Notes'), '/notes');?></li>
				<li><a href="http://jewishlexicon.weebly.com" target="_blank"><?= __('JEL Forum') ?></a></li>
				<li><a href="http://jewish-languages.org" target="_blank">Jewish-Languages.org</a></li>
				<li><a href="https://www.jewishlanguages.org/lexicon-recording"><?= __('Volunteer') ?></a></li>
				<li><a href="https://www.givecampus.com/schools/HebrewUnionCollegeJewishInstituteofReligion/help-newcomers-pronounce-jewish-words"><?= __('Donate') ?></a></li>
			</ul>
			<ul>
				<li class="first"><?php echo $this->Html->link(__('The Lexicon'), '/');?></li>
				<li><?php echo $this->Html->link(__('Home'), '/');?></li>
				<li><?php echo $this->Html->link(__('Alphabetical'), '/alphabetical');?></li>
				<li><?php echo $this->Html->link(__('Random'), '/random');?></li>
				<li><?php echo $this->Html->link(__('Add a New Word'), '/add');?></li>
			</ul>
			<div class="right">
			<?php echo $this->Html->image("joint_logo.jpg", ['id' => 'jointlogo']);?>
			<p><?= $sitelang->name ?> - (C) 2012-present, Sarah Bunin Benor. Attribution: Creative Commons <a href="https://creativecommons.org/licenses/by-sa/4.0/">Share-Alike</a> 4.0 International.</a></p>
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
</body>


</html>
