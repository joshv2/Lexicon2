<?php if (!empty($alternates)): 
    if (count($alternates) > 360): ?>
    <h4><?=__("Alternative Spellings")?></h4>	
    <p>
        <span class="more" id='addsp'><?= $spellingList; ?></span>
    </p>
    <?php else: ?>
        <h4><?=__("Alternative Spellings")?></h4>
        <p><?= $spellingList; ?></p>
    <?php endif; ?>
<?php endif; ?>