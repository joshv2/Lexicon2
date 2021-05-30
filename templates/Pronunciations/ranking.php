<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pronunciation $pronunciation
 */
?>
<div class="column-responsive column-80">
    <div class="pronunciations form content">
        <p>Pronunciations are shown in their current ranking</p>
        <?= $this->Form->create() ?>
        <table>

            <?php echo $this->Html->tableHeaders(['Spelling', 'Listen', 'Pronunciation', 'Notes', '', 'Ranking']);
                $i = 0; ?>
            
            <?php foreach ($requested_pronunciations as $p): ?>
                <?php 
                    
                    if ('' !== $p->sound_file){
                        $audioPlayer = $this->Html->media($p->sound_file, ['pathPrefix' => 'recordings/', 'controls']);
                    } else {
                        $audioPlayer = '';
                    }
                    ?>
                <?php echo $this->Html->tableCells([[$p->spelling, $audioPlayer, $p->pronunciation, $p->notes, $this->Form->hidden('pronunciations.' . $i . '.id', ['value' => $p->id]), $this->Form->control('pronunciations.' . $i . '.display_order')]]); 
                        $i += 1; ?>
            <?php endforeach; ?>
        </table>
        <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>


