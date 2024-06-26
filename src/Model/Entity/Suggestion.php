<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Suggestion Entity
 *
 * @property int $id
 * @property int $word_id
 * @property string $user_id
 * @property string $full_name
 * @property string $email
 * @property \Cake\I18n\FrozenTime $created
 * @property string $status
 * @property string $suggestion
 *
 * @property \App\Model\Entity\Word $word
 * @property \CakeDC\Users\Model\Entity\User $user
 */
class Suggestion extends Entity
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
        'user_id' => true,
        'full_name' => true,
        'email' => true,
        'created' => true,
        'status' => true,
        'suggestion' => true,
        'word' => true,
        'user' => true,
    ];
}
