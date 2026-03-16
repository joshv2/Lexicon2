<?php
/**
 * @var \App\View\AppView $this
 * @var array|\Cake\Datasource\ResultSetInterface $alternates
 * @var string $spellingList
 * @var int|string|null $word_id
 */

$isSuperuser = ($this->request->getSession()->read('Auth.role') === 'superuser');
?>

<?php if (!empty($alternates) || $isSuperuser): ?>
    <h4><?= __("Alternative Spellings") ?></h4>
    <div class="pronunciation-section">
        <div class="table-container">
            <?php if (!empty($alternates)): ?>
                <?php if (count($alternates) > 360): ?>
                    <p>
                        <span class="more" id='addsp'><?= $spellingList; ?></span>
                    </p>
                <?php else: ?>
                    <p><?= $spellingList; ?></p>
                <?php endif; ?>
            <?php elseif ($isSuperuser): ?>
                <p><?= __('No alternate spellings yet.') ?></p>
            <?php endif; ?>
        </div>

        <?php if ($isSuperuser && !empty($word_id)): ?>
            <div class="buttons-container">
                <p>
                    <?= $this->Html->link(
                        '<i class="fa-solid fa-pen-to-square"></i> ' . __('Manage alternates'),
                        ['prefix' => false, 'controller' => 'Alternates', 'action' => 'manage', $word_id],
                        ['class' => 'button blue', 'escape' => false]
                    ); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>