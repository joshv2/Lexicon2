<?php
    $allowTags = '<a><b><i><em><strong><u><p><br><ul><ol><li>';
    $hasItems = !empty($newortd);
    $manageLabel = $manage_label ?? __('Manage ' . strtolower($header));
?>

<h4><?= __($header) ?></h4>

<?php if ($this->Identity->isLoggedIn()): ?>
    <div class="pronunciation-section">
        <div class="table-container">
            <?php if ($hasItems): ?>
                <ul class="multiple-items">
                    <?php foreach ($newortd as $s): ?>
                        <li><?= strip_tags($s, $allowTags) ?></li>
                    <?php endforeach; ?>

                    <?php if (!empty($otherortd)): ?>
                        <?php foreach ($otherortd as $s): ?>
                            <li><?= trim(strip_tags($s, $allowTags)) ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ($totalortd > 3): ?>
                        <li class="view-more-link">
                            <a href="#"><?= __("View More") ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php else: ?>
                <ul>
                    <li><?= __("None") ?></li>
                </ul>
            <?php endif; ?>
        </div>

        <div class="buttons-container">
            <p>
                <?= $this->Html->link(
                    '<i class="fa-solid fa-pen-to-square"></i> ' . $manageLabel,
                    '/' . $edit_controller . '/word/' . $word_id,
                    ['class' => 'button blue', 'escape' => false]
                ); ?>
            </p>
        </div>
    </div>
<?php else: ?>
    <?php if ($hasItems): ?>
        <ul class="multiple-items">
            <?php foreach ($newortd as $s): ?>
                <li><?= strip_tags($s, $allowTags) ?></li>
            <?php endforeach; ?>

            <?php if (!empty($otherortd)): ?>
                <?php foreach ($otherortd as $s): ?>
                    <li><?= trim(strip_tags($s, $allowTags)) ?></li>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($totalortd > 3): ?>
                <li class="view-more-link">
                    <a href="#"><?= __("View More") ?></a>
                </li>
            <?php endif; ?>
        </ul>
    <?php else: ?>
        <ul>
            <li><?= __("None") ?></li>
        </ul>
    <?php endif; ?>
<?php endif; ?>
