<!DOCTYPE html>
<html lang="en">
<head>
<?= $this->Html->charset() ?>
	<meta name="viewport" content="width=device-width" />
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
	<title><?php echo (isset($title)) ? $title . ' - Jewish English Lexicon' : 'Jewish English Lexicon'; ?></title>
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
    
<?php 
 echo '<a id="play-pause-button-0" class="fa fa-volume-up"> <span id="listen">listen</span></a><video controls="controls" class="audioplayers" id="audioplayer0" src="/recordings/adar16244732060.mp3"></video>';

					 ?>
</body>
</html>