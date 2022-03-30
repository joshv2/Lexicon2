<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SentenceRecording Entity
 *
 * @property int $id
 * @property int $sentence_id
 * @property string $sound_file
 *
 * @property \App\Model\Entity\Sentence $sentence
 */
class SentenceRecording extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'sentence_id' => true,
        'sound_file' => true,
        'sentence' => true,
        'notes' => true,
        'display_order' => true,
        'approved' => true,
        'approved_date' => true,
        'approving_user_id' => true,
        'user_id' => true,
    ];
}
