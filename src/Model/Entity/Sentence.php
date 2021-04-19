<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Sentence Entity
 *
 * @property int $id
 * @property int $word_id
 * @property string $sentence
 *
 * @property \App\Model\Entity\Word $word
 * @property \App\Model\Entity\SentenceRecording[] $sentence_recordings
 */
class Sentence extends Entity
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
        'word_id' => true,
        'sentence' => true,
        'word' => true,
        'sentence_recordings' => true,
    ];
}
