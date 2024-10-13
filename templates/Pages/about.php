<section id="main">
<div id="header-image">
<?php echo $this->Html->image($sitelang->LogoImage, ['height' => 200, 'width' => 900])?>
 </div>
 		<div class="page-header group">
		<h2 class="left">Welcome</h2>
		</div>  
        <div id="notes" class="c content">
		<p><?= __("Welcome to JEL, a collaborative database of distinctive words that are used in the speech or writing of English-speaking Jews. Think of it as the Wikipedia or Urban Dictionary of Jewish language.")?></p>

		<p><?=__("The words in this database stem from several languages of the Jewish past and present, including the Hebrew and Aramaic of ancient biblical and rabbinic texts, the Yiddish of centuries of Jewish life in Eastern Europe, and the Modern Hebrew of contemporary Israel. When Jews use words from this list within their English speech or writing, they indicate not only that they are Jewish but also that they are a certain type of Jew. Some are Yiddish lovers, some are engaged in religious life and learning, some have a strong connection to Israel, some have Sephardi heritage, and some are all of the above. Because Jewish and non-Jewish social networks overlap, these words are not used exclusively by Jews. Some are English words that certain Jews use in distinctive ways, and some are Yiddish-origin words that have become part of the English language.")?></p>

		<p><?= __("JEL was started in 2007 as a class project in") ?><a href="http://huc.edu/faculty/faculty/benor.shtml"><?=__("Sarah Bunin Benor")?></a><?=__('s course "American Jewish Language and Identity in Historical Context" at')?> <a href="http://www.huc.edu/"><?=__("Hebrew Union College – Jewish Institute of Religion")?></a> <?=__("in Los Angeles. Students were asked to contribute words that they heard from their friends or read online or in print, as well as definitions, example sentences, and source languages. Many of the entries already appeared in one or more of the ")?><?php echo $this->Html->link(__('published Jewish English Dictionaries'), '/notes')?><?=__( ", but many appear here for the first time, thanks to the 
		students and visitors like you'.") ?> </p>

		<p> <?= __("By design, JEL is a work in progress. We hope you will help us build and refine it. If you notice a word missing, add it. If you want to suggest an edit to an existing entry, you can click the \"edit\" button on that entry. All changes are moderated. We invite you to experience the lexicon by browsing, searching, sorting, and, most importantly, adding new entries. Past contributors have reported that once they started paying attention, their eyes (and ears) were opened to a whole new world of Jewish English language. ")?></p>

		<p id="enterthelexicon">
		<?php echo $this->Html->link(__('Enter the Lexicon'), '/', ['class' => 'button blue']);?>
		</p>
	<!--</div>-->
</div>
	<div class="page-header group">
		<h2 class="left">About Us</h2>
	</div>  
	<div id="notes" class="c content">
	<!--<div id="about" class="c content">-->
		<p> <?=__("The goals of the Jewish English Lexicon (JEL) are to collect data on English spoken and written by Jews and to make it available to the public.") ?></p>
		</div>
		<h4> <?=__('JEL Team')?></h4>
		<ul class="staff">
			<li><?=__('Founding Director: Sarah Bunin Benor')?></li>
			<li><?=__('Managing Director: Hannah Kober')?></li>
			<li><?=__('Intern: Matan Kruskal')?></li>
			<li><?=__('Programmer: Josh Vogel')?></li>
			<li><?=__('Logo Designer: Simone Klein')?></li>
			<li><?=__('Pronunciation recordings, entry submissions, etc.: Many volunteers?')?></li>
		</ul>
		<div id="notes" class="c content">
		<p><?=__('You can learn more about the team')?> <?php echo $this->Html->link(__('here.'), 'https://www.jewishlanguages.org/jewish-language-project');?><?=__("If you're interested in contributing or volunteering, ")?><?php echo $this->Html->link(__('please let us know.'), 'https://www.jewishlanguages.org/donate');?></p>

		<p><?=__('Past team members: Isaac Bleaman, Noam Fields-Meyer, Leah Helfgott, Tsvi Sadan, Chaim Singer-Frankes, Eliran Sobel, and David Voong.')?></p>
		<p><?=__('Photo credits:')?> <?php echo $this->Html->link(__('Bill Aron'), 'http://www.billaron.com/');?> (1, 3), <?php echo $this->Html->link(__('Kim Silverstein'), 'http://www.silverliningphotosbykim.com/');?> <?=__("(2), Francine Zara Mathur/Sasha Aleiner/")?><?php echo $this->Html->link('Jews in ALL Hues', 'http://www.jewsinallhues.org/');?> <?=__("(4), Ra'anan Boustan (5) and Mijal Bitton (6).")?> </p>
		<div class="clear m3"></div>
		<h3><?=__('Top word contributors')?></h3>
		
		<p><?=__('The people listed here contributed many entries to the JEL.')?></p>

		<ul>
			<li><?=__('Mark Bunin Benor')?></li>
			<li><?=__('Isaac Bleaman')?></li>
			<li><?=__('Alexandra Casser')?></li>
			<li><?=__('Rabbi Jordana Chernow-Reader')?></li>
			<li><?=__('Noam Fields-Meyer')?></li>
			<li><?=__('Matan Kruskal')?></li>
			<li><?=__('Rabbi Lydia Bloom Medwin')?></li>
			<li><?=__('Rabbi Daniel Bloom Medwin')?></li>
			<li><?=__('Rabbi Joshua Samuels')?></li>
			<li><?=__('Eliran Sobel')?></li>
		</ul>

		<p class="m3"><?=__('You too can be listed here! After you have added several entries, please let us know. Names will only be added with permission.')?></p>

		<h3><?=__('Funding')?></h3>
		
		<p><?=__('The JEL is an initiative of the HUC-JIR ')?><?php echo $this->Html->link(__('Jewish Language Project'), 'https://www.jewishlanguages.org/jewish-language-project');?> <?=__('and is made possible by generous funding from:')?></p>

		<ul>
			<li><?=__('Hebrew Union College - Jewish Institute of Religion')?></li>
			<li><?=__('Dorot Foundation')?></li>
			<li><?=__('American Academy for Jewish Research Special Initiatives Fund')?></li>
			<li><?=__('Concentration in Education and Jewish Studies at Stanford University')?></li>
			<li><?=__('Jack, Joseph and Morton Mandel Center for Studies in Jewish Education at Brandeis University')?></li>
			<li><?=__('Mark David')?></li>
			<li><?=__('Elyssa Elbaz')?></li>
			<li><?=__('Alicia')?></li>
		</ul>

		<p><?php echo $this->Html->link(__('Donations'), 'https://www.givecampus.com/schools/HebrewUnionCollegeJewishInstituteofReligion/help-newcomers-pronounce-jewish-words');?> <?=__('are always welcome! If you contribute $360 or more, you will have the option of being listed here.')?></p>
		</div>
		<div class="page-header group">
		<h2 class="left"><?=__('Contact Us')?></h2>
		</div>
		<div id="about2" class="c content">
		<p><?=__('You can add an entry to the lexicon ')?><?php echo $this->Html->link(__('here'), '/add');?>. <?=__('To edit an existing entry, click the Edit button in the full entry. If you have general questions or comments about the site, you can contact us ')?><a href="https://www.jewishlanguages.org/contact"><?=__('here')?></a>.</p>
		</div>

	<div class="page-header group">
		<h2 class="left"><?=__('Citation')?></h2>
	</div>
   
	<div id="about2" class="c content">
	<p><?=__('To cite the Lexicon in general:')?></br>
	<?=__('Benor, Sarah Bunin. 2012-present. "Jewish English Lexicon." Los Angeles: Jewish Language Project. https://jel.jewish-languages.org/.')?></p>

<p><?=__('To cite a particular entry:')?></br>
<?=__("Benor, Sarah Bunin. 2012-present. \"Tachlis, entry in Jewish English Lexicon.\" Los Angeles: Jewish Language Project. https://jel.jewish-languages.org/words/556. ")?> </p>
	</div>

	<div class="page-header group">
		<h2 class="left"><?=__('Privacy')?></h2>
	</div>
	<div id="about22" class="c content">
	<p><?=__('The Lexicon collects minimal data about visitors to the site. Any data which is or will be collected by this site will be covered by the')?> <?php echo $this->Html->link(__('HUC privacy policy'), 'https://huc.edu/privacy-policy/');?> <?=__('and')?> <?php echo $this->Html->link(__('HUC cookie policy'), 'https://huc.edu/cookie-policy/');?>.</br>
	</div>
</section>
