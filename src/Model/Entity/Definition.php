<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Definition Entity
 *
 * @property int $id
 * @property int $word_id
 * @property string $definition
 *
 * @property \App\Model\Entity\Word $word
 */
class Definition extends Entity
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
        'definition' => true,
        'definition_json' => true,
        'word' => true,
    ];
}
