<section id="main">
	<div id="header-image">
	<?php echo $this->Html->image('header3.jpg', ['height' => 200, 'width' => 900])?>
 </div>
 <div class="page-header group">
		<h2 class="left"><?= __('Notes & Disclaimers')?></h2>
	</div>
     
	<div id="notes" class="c content">

		<h3><?=__("Spelling")?></h3>
		<p class="m3"><?=__("In English writing, Hebrew and Yiddish words can be spelled in many different ways. Some publishers use the Library of Congress system for Hebrew and the YIVO system for Yiddish. But, as we explain below, words used in English often come from both Hebrew and Yiddish, and visitors might think it was strange to see one system or the other (“Five Books of Moses” and “Jewish law” would be either khumesh and halokhe or ḥumash and halakha). Another option was to go with spellings provided in Jewish English dictionaries, but they have diverse spellings too. Instead, we appealed to a higher authority:")?> <a href="http://www.google.com/advanced_search"><?=__("Google")?></a>. <?=__("Whichever spelling had the most hits in English at the time it was entered is listed in the primary spot. For example, “al regel achat” had 8,540 hits, while “al regel ahat” had only 5,860. At the same time, we want to make sure that visitors searching for a specific word could find it, so we offer alternative spellings too, including Ashkenazi, Israeli, and Sephardi/Mizrahi variants. The list of attested spellings is by no means exhaustive, and, as with the rest of the database, we count on visitors like you to add more.")?></p>

		<h3><?=__("Languages of origin")?></h3>
		<p><?=__("It is often difficult to determine a word’s language of origin. Many of the words that derive originally from textual Hebrew or Aramaic were also incorporated into Yiddish and are now used in English with Yiddish influences in meaning and pronunciation. Many words are used in English today because speakers encounter them in the biblical and rabbinic texts that are so central to Jewish religious life. Israeli Hebrew also provides some pronunciation norms and several new words (for details, see Benor’s ")?><a href="http://www.bjpa.org/Publications/details.cfm?PublicationID=4799"><?=__("2000 paper")?></a><?=__("and")?>  <a href="http://becomingfrum.weebly.com/"><?=__("2012 book")?></a>). <?=__("Despite this overlap, we include source language information due to popular demand. Here is a guide to our language names:")?></p>
		<ul class="m3">
			<li><b><?=__("English:")?></b><?=__( "Words of English stock used distinctly by Jews, some because of Yiddish or Hebrew influence, others based on context.")?></li>
			<li><b><?=__("Textual Hebrew:")?></b><?=__(" Hebrew in the Bible, ancient or medieval rabbinic literature like the Talmud and responsa, and liturgy.")?></li>
			<li><b><?=__("Aramaic:")?></b><?=__( "Judeo-Aramaic, especially as it appears in the Talmud and some liturgy.")?></li>
			<li><b><?=__("Yiddish:")?></b><?=__( "Eastern Yiddish (a German-based language with Hebrew/Aramaic, Slavic, and Romance influences) spoken by Ashkenazi Jews in Eastern Europe (Poland, Lithuania, Hungary, etc.)")?>.</li>
			<li><b><?=__("Modern Hebrew:")?></b> <?=__("Words used in contemporary Israeli Hebrew and/or coined in the modern era.")?></li>
			<li><b><?=__("Ladino:")?></b> <?=__("Ottoman Judezmo/Judeo-Spanish (a Spanish-based language with Turkish, Greek, Arabic and Balkan influences) spoken by Sephardim especially in the Ottoman Empire.")?></li>
			<li><b><?=__("Arabic:")?></b><?=__( "Arabic and Judeo-Arabic spoken in Arab lands. Some Arabic words entered English through Modern Hebrew, and others through the Judeo-Arabic and Ladino of immigrants to English-speaking countries from Muslim lands.")?></li>
		</ul>

		<h3><?=__("Who tends to use it")?></h3>

		<p><?=__("A lexicon is more comprehensive when it includes sociolinguistic information. While any Jew or non-Jew might use any of the words in this lexicon, research has shown that certain types of people are more likely to use certain words (see ")?><a href="http://www.bjpa.org/Publications/details.cfm?PublicationID=3874"><?=__("Benor and Cohen’s 2009 survey results")?></a><?=__("and")?> <a href="http://huc.edu/sites/default/files/people/pdf/benor/LAC_421%5B1%5D%20Proofs%20October%202010.pdf"><?=__("Benor’s 2011 academic paper")?></a><?=__("). The groups we included are based mostly on Benor and Cohen’s survey data:")?></p>

		<ul>
			<li><b><?=__("Religious:")?></b> <?=__("Jews who are engaged in religious observance and have some Jewish education")?></li>
			<li><b><?=__("Orthodox:")?></b> <?=__("Jews who identify as Orthodox and observe halacha (Jewish law)")?></li>
			<li><b><?=__("Organizations:")?></b><?=__("People involved in a professional or volunteer capacity with Jewish nonprofit organizations")?></li>
			<li><b><?=__("Jews:")?></b> <?=__("Jews of diverse religious backgrounds and organizational involvements")?></li>
			<li><b><?=__("Camp:")?></b> <?=__("Jews who attend or work at a Jewish overnight summer camp")?></li>
			<li><b><?=__("Israel:")?></b> <?=__("Diaspora Jews who feel connected to Israel and have spent time there")?></li>
			<li><b><?=__("Ethnic:")?></b> <?=__("Jews whose Jewish identity is primarily ethnic")?></li>
			<li><b><?=__("Older:")?></b> <?=__("Jews who are middle-aged and older")?></li>
			<li><b><?=__("Younger:")?></b> <?=__("Jews in their 30s or younger")?></li>
			<li><b><?=__("Ashkenazim:")?></b> <?=__("Jews with Ashkenazi heritage")?></li>
			<li><b><?=__("Sephardim:")?></b> <?=__("Jews with Sephardi or Mizrahi heritage")?></li>
			<li><b><?=__("Syrian")?></b>: <?=__("Jews with recent ancestry in Syria")?></li>
			<li><b><?=__("Persian")?></b>: <?=__("Jews with recent ancestry in Iran")?></li>
			<li><b><?=__("Russian")?></b>: <?=__("Jews with recent Russian-speaking ancestry in Russia")?></li>
			<li><b><?=__("Non-Jews:")?></b> <?=__("(words that have spread outside of Jewish networks)")?></li>
			<li><b><?=__("Chabad:")?></b> <?=__("Jews affiliated with the Chabad Lubavitch movement")?></li>
		</ul>

		<p class="m3"><?=__("These categories are not intended to represent all subdivisions of American Jews, and they are by no means mutually exclusive; some individuals are part of six or more of the groups. In the absence of research on most of the words in this lexicon, this column is based mostly on the impressions of the people who entered each word; feel free to edit as you see fit. As this column suggests, Jewish English is not a homogenous language but rather an umbrella category for English spoken by a diverse group of people.")?></p>

		<h3><?=__("Jewish English Dictionaries cited in the database")?></h3>
		<ul>
			<li><a href="http://www.amazon.com/New-Joys-Yiddish-Completely-ebook/dp/B003FCTYWU/ref=sr_1_4?ie=UTF8&qid=1321397977&sr=8-4"><em><?=__("The New Joys of Yiddish")?></em><?=__(", by Leo Rosten and Lawrence Bush (New York, 2003[1968]).")?></li></a>
			<li><a href="http://www.amazon.com/Yiddish-English-America-Judaic-Studies/dp/0817311033/ref=sr_1_10?s=books&ie=UTF8&qid=1321398146&sr=1-10"><em><?=__("Yiddish and English: A Century of Yiddish in America")?></em><?=__(", by Sol Steinmetz (Tuscaloosa, 1986).")?></li></a>
			<li><a href="http://www.amazon.com/The-Joys-of-Hebrew-ebook/dp/B003XVZYG8/ref=sr_1_2?ie=UTF8&qid=1321397848&sr=8-2"><em><?=__("The Joys of Hebrew")?></em><?=__(", by Lewis Glinert (New York, 1992).")?></li></a>
			<li><a href="http://www.amazon.com/Frumspeak-Dictionary-Yeshivish-Chaim-Weiser/dp/1568216149/ref=sr_1_1?ie=UTF8&qid=1321397903&sr=8-1"><em><?=__("Frumspeak: The First Dictionary of Yeshivish")?></em><?=__(", by Chaim Weiser (Northvale, 1995).")?></li></a>
			<li><a href="http://www.amazon.com/Dictionary-Jewish-Words-JPS-Guide/dp/0827608322/ref=sr_1_1?s=books&ie=UTF8&qid=1321398335&sr=1-1"><em><?=__("The JPS Dictionary of Jewish Words")?></em><?=__(", by Joyce Eisenberg and Ellen Scolnic, (Philadelphia, 2001).")?></li></a>
			<li><a href="http://www.amazon.com/Dictionary-Jewish-Usage-Guide-Terms/dp/0742543870/ref=sr_1_7?s=books&ie=UTF8&qid=1321398146&sr=1-7"><em><?=__("Dictionary of Jewish Usage: A Guide to the Use of Jewish Terms")?></em><?=__(", by Sol Steinmetz (Lanham, MD, 2005).")?></li></a>
		</ul>
	</div>

<script>
var sc_project=7851936;
var sc_invisible=1;
var sc_security="d9e1dc98";
</script>
<script src="http://www.statcounter.com/counter/counter.js"></script>
<noscript><a class="statcounter" href="http://statcounter.com/"><img class="statcounter" src="http://c.statcounter.com/7851936/0/d9e1dc98/1/" alt="StatCounter" /></a></noscript>

</section>
