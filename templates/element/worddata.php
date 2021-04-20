<?php //$loggedIn = false;
	if(!empty($worddataarray)):?>
        <h4><?php echo $title; ?></h4>
        <ul class="origins">
        <?php foreach ($worddataarray as $w): ?>
            <li><?php echo $w;?></li>
        <?php endforeach; ?>
        </ul>
    <?php endif;?>
	