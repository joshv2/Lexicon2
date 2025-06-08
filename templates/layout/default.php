<!DOCTYPE html>
<html lang="<?= $sitelang->i18nspec ?>">
<head>
	<?= $this->Html->charset() ?>
	<meta name="viewport" content="width=device-width" />
	<link rel="icon" 
      type="image/ico" 
      href="/img/<?= $sitelang->i18nspec ?>/favicon.ico" />
	<script>
					
		// Function to set a cookie
		function setCookie(name, value, days) {
		var expires = "";
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = "; expires=" + date.toUTCString();
		}
		document.cookie = name + "=" + (value || "") + expires + "; path=/";
		}

		// Function to save consent data to a cookie
		function saveConsentToCookie(consentData) {
			var consentValue = consentData;
			setCookie('consentCookie', consentValue, 7); // Save for 7 days
		}

		

		// Get consent status and save to cookie
		/*document.addEventListener("DOMContentLoaded", function() {
			getConsentStatus(function(consentData) {
				saveConsentToCookie(consentData);
			});
		});*/
	</script>
	<script>
		// Define dataLayer and the gtag function.
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}

		// Set default consent to 'denied' as a placeholder
		// Determine actual values based on your own requirements
		gtag('consent', 'default', {
		'ad_storage': 'denied',
		'ad_user_data': 'denied',
		'ad_personalization': 'denied',
		'analytics_storage': 'denied'
		});
	</script>
	
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','<?= $sitelang->googleAnalytics ?>');</script>
	<!-- End Google Tag Manager -->
	
	<link rel="stylesheet" href="https://public-assets.tagconcierge.com/consent-banner/1.2.3/styles/light.css" />
	 
	 
	 
	  <!--<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2080909425204614"
     crossorigin="anonymous"></script>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({
		google_ad_client: "ca-pub-2080909425204614",
		enable_page_level_ads: true,
		overlays: {bottom: true}
	});
	</script>-->
	
	
	<?php echo $this->Html->css('output.css');?>
	<?php echo $this->Html->css('checkboxes.css');?>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
	
	<?php echo $this->Html->css('fontawesome');?>
	<?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>
	<!-- Include stylesheet -->
	<!--<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill-better-table@1.2.10/dist/quill-better-table.min.css">

	<?php echo $this->Html->css('style');?>
	<title><?php echo (isset($title)) ? $title . ' - ' . $sitelang->name : $sitelang->name; ?></title>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->



	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
	<?= $this->Html->script('addform')."\n";?>
	<?= $this->Html->script('audioplayback')."\n";?>
	<?= $this->Html->script('exConfirm')."\n";?>
	<?= $this->Html->script('recorder')."\n";?>
	<?= $this->Html->script('toggle')."\n";?>
	<?= $this->Html->script('fontawesome')."\n";?>
	<?= $this->Html->script('solid')."\n";?>
	<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
	<!--<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>-->
	<?= $this->Html->script('detectios')."\n";?>
	<script src='https://www.google.com/recaptcha/api.js?hl=<?= $sitelang->i18nspec ?>'></script>

</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= $sitelang->googleAnalytics ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<header>
  	<div class="container bannerbg">
			<nav class="navbar navbar-expand-lg navbar-dark bg-gradient-dark">
			<!--<a class="navbar-brand" href="/">-->
				<?php echo $this->Html->image($sitelang->i18nspec . '/JewishLex_Logo.jpg' , 
                                            [
                                            'class' => "d-inline-block align-top",
                                            'alt'=> "Home Logo",
                                            'height' => 51, 
                                            'url' => ['controller' => 'Pages', 'action' => 'index', 'plugin' => false]
                                            ])?>
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
	<!--Extra ads-->
	<!--<script type="text/javascript"> var infolinks_pid = 3416121; var infolinks_wsid = 0; </script> 
	<script type="text/javascript" src="//resources.infolinks.com/js/infolinks_main.js"></script>-->
	<!--End extra ads -->
	<script>
        var config = {
            display: {
                mode: "bar"
            },
            consent_types: [{
                name: 'analytics_storage',
                title: "Analytics storage",
                description: 'Enables storage, such as cookies, related to analytics (for example, visit duration)',
                default: 'denied'
            }, {
                name: "ad_storage",
                title: "Ads storage",
                description: "Enables storage, such as cookies, related to advertising [link](https://www.google.com)",
                default: 'denied'
            }, {
                name: 'ad_user_data',
                title: "User Data",
                description: 'Sets consent for sending user data to Google for online advertising purposes.',
                default: 'denied'
            }, {
                name: 'ad_personalization',
                title: "Personalization",
                description: '  Sets consent for personalized advertising.',
                default: 'denied'
            }],
            settings: {
                title: "Cookies Settings",
                description: "Check the cookies you accept.",
                buttons: {
                    save: "Save preferences",
                    close: "Close"
                }
            },
            modal: {
                title: 'Cookies',
                description: 'We are using various cookies files. Learn more in our [privacy policy](/about#about22) and make your choice.',
                buttons: {
                    accept: 'Accept',
                    settings: 'Settings'
                }
            }
        };
    </script>
	<script src="https://public-assets.tagconcierge.com/consent-banner/1.2.3/cb.min.js" integrity="sha384-zXUdInIfEJI2FEImKEFc2cmja+Jn7TViSXzqt6OhABX0jMgz6Mctrc864uJaN5PX" crossorigin="anonymous"></script>
	<script>
      cookiesBannerJs(
        function() {
          try {
            return JSON.parse(localStorage.getItem('consent_preferences'));
          } catch (error) {
            return null;
          }
        },
        function(consentState) {
          gtag('consent', 'update', consentState);
          localStorage.setItem('consent_preferences', JSON.stringify(consentState));
		  saveConsentToCookie(localStorage.getItem('consent_preferences'));
        },
        config
      );
    </script>
</body>


</html>
