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
	
	
	<?php echo $this->Html->css('output.css');?>
	<?php echo $this->Html->css('checkboxes.css');?>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
	
	<?php echo $this->Html->css('fontawesome');?>
	<?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
	<!-- Include stylesheet -->
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <?php echo $this->Html->css('style');?>
	<title><?php echo (isset($title)) ? $title . ' - ' . $sitelang->name : $sitelang->name; ?></title>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
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
	<?= $this->Html->script('addform')."\n";?>
	<?= $this->Html->script('audioplayback')."\n";?>
	<?= $this->Html->script('exConfirm')."\n";?>
	<?= $this->Html->script('recorder')."\n";?>
	<?= $this->Html->script('toggle')."\n";?>
	<?= $this->Html->script('fontawesome')."\n";?>
	<?= $this->Html->script('solid')."\n";?>

	<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	<?= $this->Html->script('detectios')."\n";?>
	<script src='https://www.google.com/recaptcha/api.js'></script>

</head>
<body>


<header>
  	<div class="container bannerbg">
			<nav class="navbar navbar-expand-lg navbar-dark bg-gradient-dark">
			<!--<a class="navbar-brand" href="/">-->
				<?= $this->cell('Logo'); ?>
			<!--</a>--->
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto mx-auto">
					<li class="nav-item">
						<?php echo $this->Html->link(__('Home'), '/', ['class' => "nav-link"]);?>	
					</li>
					<li class="nav-item">
						<?php echo $this->Html->link(__('Add a Word'), '/add', ['class' => "nav-link"]);?>
					</li>
					<li class="nav-item">
						<?php echo $this->Html->link(__('About Us'), '/about', ['class' => "nav-link"]);?>
					</li>
					<li class="nav-item">
						<?php echo $this->Html->link(__('Notes'), '/notes', ['class' => "nav-link"]);?>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://www.jewishlanguages.org">Jewish Languages <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://www.jewishlanguages.org/lexicon-recording"><?= __('Volunteer');?> <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item last">
						<a class="nav-link" href="https://www.givecampus.com/schools/HebrewUnionCollegeJewishInstituteofReligion/help-newcomers-pronounce-jewish-words"><?= __('Donate');?> <span class="sr-only">(current)</span></a>
					</li>
				</ul>

			<form class="form-inline" type="GET" action="/search">
				<input class="form-control mr-sm-2" type="text" results="5" placeholder=<?= __("Search...");?> aria-label="Search" name="q" id="search">
			</form>
		</div> <!-- nav -->
			</nav>
	</div>

	</header>
<div>
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
<div>


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
			<?php echo $this->Html->image("joint_logo.jpg", ['id' => 'jointlogo', 'width' => '100%']);?>
			<p><?= $sitelang->name ?> - (C) 2012-present, Sarah Bunin Benor. Attribution: Creative Commons <a href="https://creativecommons.org/licenses/by-sa/4.0/">Share-Alike</a> 4.0 International .</a></p>
			</div>
			<div class="clear"></div>
		</div>
  </footer>
  <?= $this->Html->script('ortdselectall')."\n";?>
	<?= $this->Html->script('bottom')."\n";?>
	<script type="text/javascript"> var infolinks_pid = 3416121; var infolinks_wsid = 0; </script> 
	<script type="text/javascript" src="//resources.infolinks.com/js/infolinks_main.js"></script>
</body>


</html>
