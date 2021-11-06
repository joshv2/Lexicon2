<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AlphabetAddendum Entity
 *
 * @property int $id
 * @property int $language_id
 * @property int $UTF8value
 *
 * @property \App\Model\Entity\Language $language
 */
class AlphabetAddendum extends Entity
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
        'id' => true,
        'language_id' => true,
        'UTF8value' => true,
        'language' => true,
    ];
}
