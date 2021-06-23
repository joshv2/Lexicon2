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
}
?>
<nav id="crumbs" class="group">
	<?php echo $this->element('user_bar');?>
</nav>
<div class="column-responsive column-80">
<h2><?php echo 'Order pronunciations for ' . $this->Html->link($word->spelling, ['controller' => 'Words', 'action' => 'view', $word->id]); ?></h2>
    <div class="pronunciations form content">
        <p>Pronunciations are shown in their current ranking. Ranking is for approved pronunciations only.</p>
        <?= $this->Form->create() ?>
        <table>

            <?php echo $this->Html->tableHeaders(['Spelling', 'Listen', 'Pronunciation', '', 'Ranking']);
                $i = 0; ?>
            
            <?php foreach ($requested_pronunciations as $p): ?>
                <?php if(1 == $p->approved): ?>
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
                                                    $this->Form->hidden('pronunciations.' . $i . '.id', ['value' => $p->id]), 
                                                    $this->Form->control('pronunciations.' . $i . '.display_order', ['label' => false])
                                                   ]]); 
                        $i += 1; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
        <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
        
        <hr/>
       
<h2>Delete/Approve Pronunciations</h2>       
<table>

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
                                                    (!empty($p->user->username)) ? $p->user->username : '',
                                                    $this->Form->hidden('pronunciations.' . $i . '.id', ['value' => $p->id]), 
                                                    $this->Form->postLink(
                                                        '<i class="icon-trash"></i> Delete',
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
        </table>

    </div>


