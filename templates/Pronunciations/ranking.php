<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pronunciation $pronunciation
 */
?>
<?php function status_to_words($approved){
	switch ($approved) {
		case 0:
			return "Pending";
		case 1:
			return "Approved";
		case -1:
			return "Denied";
	}
} // TODO: Find a better solution for this
?>
<div class="column-responsive column-80">
    <ul class="tabs">
        <li class="tab-link current" data-tab="tab-1">Approval</li>
        <li class="tab-link" data-tab="tab-2">Change Ordering</li>
    </ul>

    <div id="tab-1" class="tab-content current">
        <h2 class="approval-header"><?=__("Delete/Approve Pronunciations")?></h2>       
        <table id="approval-table">
            <?php echo $this->Html->tableHeaders(['Spelling', 'Listen', 'Pronunciation', 'Status','Username','', '','', '']);
                $i = 0; ?>
            <?php foreach ($requested_pronunciations as $p): ?>
                <?php 
                    if ('' !== $p->sound_file){
                        $audioPlayer = $this->Html->media($p->sound_file, ['pathPrefix' => 'recordings/', 'controls']);
                    } else {
                        $audioPlayer = '';
                    }
                    ?>
                <?php echo $this->Html->tableCells([[$p->spelling, 
                                                    $audioPlayer, 
                                                    $p->pronunciation, 
                                                    status_to_words($p->approved),
                                                    (!empty($p->submitting_user->username)) ? $p->submitting_user->username : 'Submitted by Public',
                                                    $this->Form->hidden('pronunciations.' . $i . '.id', ['value' => $p->id]), 
                                                    $this->Form->postLink(__(
                                                        '<i class="fa-solid fa-trash"></i> Delete'),
                                                        ['prefix' => false, 'controller' => 'Pronunciations', 'action' => 'delete', $p->id, $word->id], 
                                                        ['confirm' => 'Are you sure you want to delete this pronunciation?', 'escape' => false, 'class' => 'button red']),
                                                    $this->Html->link('<i class="fas fa-times"></i> Deny',
                                                    ['prefix' => false, 'controller' => 'Pronunciations', 'action' => 'edit', $p->id, $word->id], ['escape' => false, 'class' => 'button orange']),
                                                    $this->Form->postLink(
                                                        '<i class="fas fa-check"></i> Approve',
                                                        ['prefix' => false, 'controller' => 'Pronunciations', 'action' => 'approve', $p->id, $word->id], 
                                                        ['confirm' => 'Are you sure you want to approve this pronunciation?', 'escape' => false, 'class' => 'button green'])
                                                   ]]); 
                        $i += 1; ?>
            <?php endforeach; ?>
        </table> <!-- TODO: Fix when status is deined to have date and also grey out and put on bottom? -->
    </div>

    <div id="tab-2" class="tab-content">
        <h2 class="ranking-header"><?php echo 'Order pronunciations for ' . $this->Html->link($word->spelling, ['controller' => 'Words', 'action' => 'view', $word->id]); ?></h2>
        <div class="pronunciations form content">
            <p class="ranking-description"><?=__("Pronunciations are shown in their current ranking. Ranking is for approved pronunciations only.")?></p>
            <?= $this->Form->create(null, ['id' => 'ranking-form']) ?>
            <table id="ranking-table">
                <?php echo $this->Html->tableHeaders(['Spelling', 'Listen', 'Pronunciation', '', 'Ranking']);
                    $i = 0; ?>
                <tbody id="sortable">
                <?php foreach ($requested_pronunciations as $p): ?>
                    <?php if(1 == $p->approved): ?>
                    <?php 
                        if ('' !== $p->sound_file){
                            $audioPlayer = $this->Html->media($p->sound_file, ['type' => 'audio/webm', 
                                                                               'pathPrefix' => 'recordings/', 
                                                                               'controls', 
                                                                               'style' => ['height: 26px; width:158px;']]);
                        } else {
                            $audioPlayer = '';
                        }
                        ?>
                    <tr>
                    <?php echo $this->Html->tableCells([[$p->spelling, 
                                                        $audioPlayer, 
                                                        $p->pronunciation, 
                                                        $this->Form->hidden('pronunciations.' . $i . '.id', ['value' => $p->id]), 
                                                        $this->Form->control('pronunciations.' . $i . '.display_order', ['label' => false, 'class' => 'ranking-input'])
                                                       ]]); 
                            $i += 1; ?>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?= $this->Form->button(__('Submit'), ['class' => 'ranking-submit']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tabs = document.querySelectorAll('.tab-link');
        var contents = document.querySelectorAll('.tab-content');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var tabId = this.getAttribute('data-tab');

                tabs.forEach(function(t) {
                    t.classList.remove('current');
                });

                contents.forEach(function(content) {
                    content.classList.remove('current');
                });

                this.classList.add('current');
                document.getElementById(tabId).classList.add('current');
            });
        });

        // Make the table rows sortable
        $("#sortable").sortable({
            update: function(event, ui) {
                // Update the display_order input values based on the new order
                $('#sortable tr').each(function(index) {
                    $(this).find('.ranking-input').val(index + 1);
                });
            }
        });
        $("#sortable").disableSelection();
    });
</script>


