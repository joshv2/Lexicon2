<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Pronunciation Entity
 *
 * @property int $id
 * @property int $word_id
 * @property string $spelling
 * @property string $sound_file
 * @property string $pronunciation
 * @property string $notes
 *
 * @property \App\Model\Entity\Word $word
 */
class Pronunciation extends Entity
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
        'spelling' => true,
        'sound_file' => true,
        'pronunciation' => true,
        'notes' => true,
        'word' => true,
    ];
}
