<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Language Entity
 *
 * @property int $id
 * @property string $name
 * @property string $subdomain
 * @property string $HeaderImage
 * @property string $AboutSec1Header
 * @property string $AboutSec1Text
 * @property string $AboutSec2Header
 * @property string $AboutSec2Text
 * @property string $AboutSec3Header
 * @property string $AboutSec3Text
 * @property string $AboutSec4Header
 * @property string $AboutSec4Text
 * @property string $NotesSec1Header
 * @property string $NotesSec1Text
 *
 * @property \App\Model\Entity\Word[] $words
 */
class Language extends Entity
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
        'name' => true,
        'subdomain' => true,
        'HeaderImage' => true,
        'AboutSec1Header' => true,
        'AboutSec1Text' => true,
        'AboutSec2Header' => true,
        'AboutSec2Text' => true,
        'AboutSec3Header' => true,
        'AboutSec3Text' => true,
        'AboutSec4Header' => true,
        'AboutSec4Text' => true,
        'NotesSec1Header' => true,
        'NotesSec1Text' => true,
        'words' => true,
    ];
}
