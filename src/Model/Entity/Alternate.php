<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Alternate Entity
 *
 * @property int $id
 * @property int $word_id
 * @property string $spelling
 *
 * @property \App\Model\Entity\Word $word
 */
class Alternate extends Entity
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
    protected array $_accessible = [
        'word_id' => true,
        'spelling' => true,
        'word' => true,
    ];
}
