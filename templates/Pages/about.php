<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
</nav>
<section id="main">
<div id="header-image">
<?php echo $this->Html->image('header3.jpg', ['height' => 200, 'width' => 900])?>
 </div>
 		<div class="page-header group">
		<h2 class="left">Welcome</h2>
		</div>  
        <div id="notes" class="c content">
		<p>Welcome to JEL, a collaborative database of distinctive words that are used in the speech or writing of English-speaking Jews. Think of it as the Wikipedia or Urban Dictionary of Jewish language.</p>

		<p>The words in this database stem from several languages of the Jewish past and present, including the Hebrew and Aramaic of ancient biblical and rabbinic texts, the Yiddish of centuries of Jewish life in Eastern Europe, and the Modern Hebrew of contemporary Israel. When Jews use words from this list within their English speech or writing, they indicate not only that they are Jewish but also that they are a certain type of Jew. Some are Yiddish lovers, some are engaged in religious life and learning, some have a strong connection to Israel, some have Sephardi heritage, and some are all of the above. Because Jewish and non-Jewish social networks overlap, these words are not used exclusively by Jews. Some are English words that certain Jews use in distinctive ways, and some are Yiddish-origin words that have become part of the English language.</p>

		<p>JEL was started in 2007 as a class project in <a href="http://huc.edu/faculty/faculty/benor.shtml">Sarah Bunin Benor</a>'s course "American Jewish Language and Identity in Historical Context" at <a href="http://www.huc.edu/">Hebrew Union College – Jewish Institute of Religion</a> in Los Angeles. Students were asked to contribute words that they heard from their friends or read online or in print, as well as definitions, example sentences, and source languages. Many of the entries already appeared in one or more of the <?php echo $this->Html->link('published Jewish English Dictionaries', '/notes');?>, but many appear here for the first time, thanks to the 
		students and visitors like you'.</p>

		<p>By design, JEL is a work in progress. We hope you will help us build and refine it. If you notice a word missing, add it. If you want to suggest an edit to an existing entry, you can click the "edit" button on that entry. All changes are moderated. We invite you to experience the lexicon by browsing, searching, sorting, and, most importantly, adding new entries. Past contributors have reported that once they started paying attention, their eyes (and ears) were opened to a whole new world of Jewish English language.</p>

		<p id="enterthelexicon">
		<?php echo $this->Html->link('Enter the Lexicon', '/', ['class' => 'button blue']);?>
		
	<!--</div>-->
</div>
	<div class="page-header group">
		<h2 class="left">About Us</h2>
	</div>  
	<div id="notes" class="c content">
	<!--<div id="about" class="c content">-->
		<p id="logo">
		<?php echo $this->Html->image('JELlogo2021.png', 
                                            ['width' => 200,
                                            'height' => 204,
											'align' => 'left',
											'style' => 'margin-right:10px'])?>
		</p>
		<p>The goals of the Jewish English Lexicon (JEL) are to collect data on English spoken and written by Jews and to make it available to the public.</p>
		</div>
		<h4>JEL Team</h4>
		<ul class="staff">
			<li>Founding Director: Sarah Bunin Benor</li>
			<li>Managing Director: Hannah Kober</li>
			<li>Intern: Matan Kruskal</li>
			<li>Programmer: Josh Vogel</li>
			<li>Logo Designer: Simone Klein</li>
			<li>Pronunciation recordings, entry submissions, etc.: Many volunteers</li>
		</ul>
		<div id="notes" class="c content">
		<p>You can learn more about the team <?php echo $this->Html->link('here', 'https://www.jewishlanguages.org/jewish-language-project');?>. If you're interested in contributing or volunteering, <?php echo $this->Html->link('please let us know', 'https://www.jewishlanguages.org/donate');?>.</p>

		<p>Past team members: Isaac Bleaman, Noam Fields-Meyer, Leah Helfgott, Tsvi Sadan, Chaim Singer-Frankes, Eliran Sobel, and David Voong.</p>
		<p>Photo credits: <?php echo $this->Html->link('Bill Aron', 'http://www.billaron.com/');?> (1, 3, 4), <?php echo $this->Html->link('Kim Silverstein', 'http://www.silverliningphotosbykim.com/');?> (2), and Ra'anan Boustan (5). </p>
		<div class="clear m3"></div>
		<h3>Top word contributors</h3>
		
		<p>The people listed here contributed many entries to the JEL.</p>

		<ul>
			<li>Mark Bunin Benor</li>
			<li>Isaac Bleaman</li>
			<li>Alexandra Casser</li>
			<li>Rabbi Jordana Chernow-Reader</li>
			<li>Noam Fields-Meyer</li>
			<li>Matan Kruskal</li>
			<li>Rabbi Lydia Bloom Medwin</li>
			<li>Rabbi Daniel Bloom Medwin</li>
			<li>Rabbi Joshua Samuels</li>
			<li>Eliran Sobel</li>
		</ul>

		<p class="m3">You too can be listed here! After you have added several entries, please let us know. Names will only be added with permission.</p>

		<h3>Funding</h3>
		
		<p>The JEL is an initiative of the HUC-JIR <?php echo $this->Html->link('Jewish Language Project', 'https://www.jewishlanguages.org/jewish-language-project');?> and is made possible by generous funding from:</p>

		<ul>
			<li>Hebrew Union College - Jewish Institute of Religion</li>
			<li>Dorot Foundation</li>
			<li>American Academy for Jewish Research Special Initiatives Fund</li>
			<li>Concentration in Education and Jewish Studies at Stanford University</li>
			<li>Jack, Joseph and Morton Mandel Center for Studies in Jewish Education at Brandeis University</li>
			<li>Mark David</li>
			<li>Elyssa Elbaz</li>
			<li>Alicia</li>
		</ul>

		<p><?php echo $this->Html->link('Donations', 'https://www.givecampus.com/schools/HebrewUnionCollegeJewishInstituteofReligion/help-newcomers-pronounce-jewish-words');?> are always welcome! If you contribute $360 or more, you will have the option of being listed here.</p>
		</div>
		<div class="page-header group">
		<h2 class="left">Contact Us</h2>
		</div>
		<div id="about2" class="c content">
		<p>You can add an entry to the lexicon <?php echo $this->Html->link('here', '/add');?>. To edit an existing entry, click the Edit button in the full entry. If you have general questions or comments about the site, you can contact us <a href="https://www.jewishlanguages.org/contact">here</a>.</p>
		</div>

	<div class="page-header group">
		<h2 class="left">Citation</h2>
	</div>
   
	<div id="about2" class="c content">
	<p>To cite the Lexicon in general:</br>
Benor, Sarah Bunin. 2012-present. "Jewish English Lexicon." Los Angeles: Jewish Language Project. https://jel.jewish-languages.org/.</p>

<p>To cite a particular entry:</br>
Benor, Sarah Bunin. 2012-present. "Tachlis, entry in Jewish English Lexicon." Los Angeles: Jewish Language Project. https://jel.jewish-languages.org/words/556.  </p>
	</div>
<script>
var sc_project=7851936;
var sc_invisible=1;
var sc_security="d9e1dc98";
</script>
<script src="http://www.statcounter.com/counter/counter.js"></script>
<noscript><a class="statcounter" href="http://statcounter.com/"><img class="statcounter" src="http://c.statcounter.com/7851936/0/d9e1dc98/1/" alt="StatCounter" /></a></noscript>

</section>
