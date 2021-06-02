<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pronunciation $pronunciation
 */
?>
  
<div class="column-responsive column-80">
<h2><?php echo 'Order pronunciations for ' . $word->spelling; ?></h2>
    <div class="pronunciations form content">
        <p>Pronunciations are shown in their current ranking</p>
        <?= $this->Form->create() ?>
        <table>

            <?php echo $this->Html->tableHeaders(['Spelling', 'Listen', 'Pronunciation', '', 'Ranking']);
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
                                                    $this->Form->hidden('pronunciations.' . $i . '.id', ['value' => $p->id]), 
                                                    $this->Form->control('pronunciations.' . $i . '.display_order')
                                                   ]]); 
                        $i += 1; ?>
            <?php endforeach; ?>
        </table>
        <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
        
        <hr/>
       
<h2>Delete Pronunciations</h2>       
<table>

            <?php echo $this->Html->tableHeaders(['Spelling', 'Listen', 'Pronunciation', '', 'Delete']);
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
                                                    $this->Form->hidden('pronunciations.' . $i . '.id', ['value' => $p->id]), 
                                                    $this->Form->postLink(
                                                        '<i class="icon-trash"></i> Delete',
                                                        ['prefix' => false, 'controller' => 'Pronunciations', 'action' => 'delete', $p->id, $word->id], 
                                                        ['confirm' => 'Are you sure you want to delete this pronunciation?', 'escape' => false, 'class' => 'button red'])
                                                   ]]); 
                        $i += 1; ?>
            <?php endforeach; ?>
        </table>

    </div>


