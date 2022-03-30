<section id="main">
	  <div id="header-image">
	  <?php echo $this->Html->image('header3.jpg', ['height' => 200, 'width' => 900])?>
 </div>
 		<div class="page-header group">
		<h2 class="left"><?=__("Welcome")?></h2>
		</div>  
        <div id="notes" class="c content">
		<!--<h3 class="m2">Welcome to the Jewish English Lexicon</h3>-->
		<p><?=__("Welcome to JEL, a collaborative database of distinctive words that are used in the speech or writing of English-speaking Jews. Think of it as the Wikipedia or Urban Dictionary of Jewish language.")?></p>

		<p><?=__("The words in this database stem from several languages of the Jewish past and present, including the Hebrew and Aramaic of ancient biblical and rabbinic texts, the Yiddish of centuries of Jewish life in Eastern Europe, and the Modern Hebrew of contemporary Israel. When Jews use words from this list within their English speech or writing, they indicate not only that they are Jewish but also that they are a certain type of Jew. Some are Yiddish lovers, some are engaged in religious life and learning, some have a strong connection to Israel, some have Sephardi heritage, and some are all of the above. Because Jewish and non-Jewish social networks overlap, these words are not used exclusively by Jews. Some are English words that certain Jews use in distinctive ways, and some are Yiddish-origin words that have become part of the English language.")?></p>

		<p><?=__("JEL was started in 2007 as a class project in")?> <a href="http://huc.edu/faculty/faculty/benor.shtml"><?=__("Sarah Bunin Benor")?></a><?=__("'s course \"American Jewish Language and Identity in Historical Context\" at")?> <a href="http://www.huc.edu/"><?=__("Hebrew Union College – Jewish Institute of Religion")?></a> <?=__("in Los Angeles. Students were asked to contribute words that they heard from their friends or read online or in print, as well as definitions, example sentences, and source languages. Many of the entries already appeared in one or more of the")?><?php echo $this->Html->link__('published Jewish English Dictionaries', '/notes');?><?=__(", but many appear here for the first time, thanks to the ")?>
		<?php echo $this->Html->link__('students and visitors like you', '/about');?>.</p>

		<p><?=__("By design, JEL is a work in progress. We hope you will help us build and refine it. If you notice a word missing, add it. If you disagree with a definition or want to add notes, please click the edit button on that entry. All changes are moderated. We invite you to experience the lexicon by browsing, searching, sorting, participating in conversations on the")?> <a href="http://jewishlexicon.weebly.com"><?=__("JEL forum")?></a><?=__(", and, most importantly, adding new entries. Past contributors have reported that once they started paying attention, their eyes (and ears) were opened to a whole new world of Jewish English language.")?></p>

		<p id="enterthelexicon">
		<?php echo $this->Html->link__('Enter the Lexicon', '/', ['class' => 'button blue']);?>
		<p><em><?=__("JEL is made possible by grants from the American Academy for Jewish Research Special Initiatives Fund and the Dorot Foundation.")?></em></p>
	</div>


</section>