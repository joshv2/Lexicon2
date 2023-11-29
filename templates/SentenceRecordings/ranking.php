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
<h1>Current Word is: <?= $this->Html->link($assocWord->spelling,  ['controller' => 'Words', 'action' => 'view', $assocWord->id], ['escape' => false]) ?></h1>
<div class="column-responsive column-80">
<h2><?php echo 'Order sentences for ' . strip_tags($assocSentence['sentence']); ?></h2>
    <div class="pronunciations form content">
        <p><?=__("Sentences are shown in their current ranking. Ranking is for approved pronunciations only.")?></p>
        <?= $this->Form->create() ?>
        <table>
            <?php echo $this->Html->tableHeaders(['Recording ID', 'Listen', '', 'Ranking']);
                $i = 0; ?>
            
            <?php foreach ($sentRecs as $p): ?>
                <?php if(1 == $p->approved): ?>
                <?php 
                    
                    if ('' !== $p->sound_file){
                        $audioPlayer = $this->Html->media($p->sound_file, ['pathPrefix' => 'recordings/', 'controls', 'class' => 'player']); //, ['type' => ''], 
                    } else {
                        $audioPlayer = '';
                    }
                    ?>
                <?php echo $this->Html->tableCells([[$p->id, 
                                                    $audioPlayer, 
                                                    $this->Form->hidden('recordings.' . $i . '.id', ['value' => $p->id]), 
                                                    $this->Form->control('recordings.' . $i . '.display_order', ['label' => false, 'class' => 'recRanking'])
                                                   ]]); 
                        $i += 1; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
        <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
        
        <hr/>
       
<h2><?=__("Delete/Approve Sentence Recordings") . " for - " . $assocSentence['sentence']?></h2>       
<table>

            <?php echo $this->Html->tableHeaders(['', 'Listen', 'Status','Username','', '','', '']);
                $i = 0; ?>
            
            <?php foreach ($sentRecs as $p): ?>
                <?php 
                    
                    if ('' !== $p->sound_file){
                        $audioPlayer = $this->Html->media($p->sound_file, ['pathPrefix' => 'recordings/', 'controls', 'class' => 'player']);
                    } else {
                        $audioPlayer = '';
                    }
                    ?>
                <?php echo $this->Html->tableCells([[$i + 1 . ".", 
                                                    $audioPlayer, 
                                                    status_to_words($p->approved),
                                                    (!empty($p->submitting_user->username)) ? $p->submitting_user->username : 'Submitted by Public',
                                                    $this->Form->hidden('sentenceRecordings.' . $i . '.id', ['value' => $p->id]), 
                                                    $this->Form->postLink(__(
                                                        '<i class="icon-trash"></i> Delete'),
                                                        ['prefix' => false, 'controller' => 'sentenceRecordings', 'action' => 'delete', $p->id], 
                                                        ['confirm' => 'Are you sure you want to delete this pronunciation?', 'escape' => false, 'class' => 'button red']),
                                                    $this->Html->link('<i class="fas fa-times"></i> Deny',
                                                    ['prefix' => false, 'controller' => 'sentenceRecordings', 'action' => 'edit', $p->id], ['escape' => false, 'class' => 'button orange']),
                                                    $this->Form->postLink(
                                                        '<i class="fas fa-check"></i> Approve',
                                                        ['prefix' => false, 'controller' => 'sentenceRecordings', 'action' => 'approve', $p->id], 
                                                        ['confirm' => 'Are you sure you want to approve this recording?', 'escape' => false, 'class' => 'button green'])
                                                   ]]); 
                        $i += 1; ?>
            <?php endforeach; ?>
        </table>

    </div>


