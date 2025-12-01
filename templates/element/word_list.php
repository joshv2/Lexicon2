<?php if (!empty($newortd)): ?>
    <h4><?= __($header) ?></h4>
    <ul class="multiple-items">
        <?php foreach ($newortd as $s): ?>
            <li><?= strip_tags($s, '<b><i><em><strong><u><p><br><ul><ol><li>') ?></li>
        <?php endforeach; ?>

        <?php if (!empty($otherortd)): ?>
            <?php foreach ($otherortd as $s): ?>
                <li><?= trim(strip_tags($s, '<b><i><em><strong><u><p><br><ul><ol><li>')) ?></li>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($totalortd > 3): ?>
            <li class="view-more-link">
                <a href="#"><?= __("View More") ?></a>
            </li>
        <?php endif; ?>
    </ul>
<?php else: ?>
    <h4><?=__($header)?></h4>
    <ul>
        <li><?=__("None")?></li>
    </ul>
<?php endif; ?>
