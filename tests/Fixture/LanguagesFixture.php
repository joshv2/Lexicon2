<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class LanguagesFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false],
        'name' => ['type' => 'string', 'length' => 255, 'null' => true],
        'subdomain' => ['type' => 'string', 'length' => 255, 'null' => true],
        'i18nspec' => ['type' => 'string', 'length' => 6, 'null' => true],
        'hasOrigins' => ['type' => 'boolean', 'null' => false, 'default' => 0],
        'hasRegions' => ['type' => 'boolean', 'null' => false, 'default' => 0],
        'hasTypes' => ['type' => 'boolean', 'null' => false, 'default' => 0],
        'hasDictionaries' => ['type' => 'boolean', 'null' => false, 'default' => 0],
        'UTFRangeStart' => ['type' => 'char', 'length' => 40, 'null' => false],
        'UTFRangeEnd' => ['type' => 'char', 'length' => 40, 'null' => false],
        'righttoleft' => ['type' => 'boolean', 'null' => false, 'default' => 0],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    public array $records = [
        [
            'id' => 1,
            'name' => 'English',
            'subdomain' => 'en',
            'i18nspec' => 'en',
            'hasOrigins' => 1,
            'hasRegions' => 1,
            'hasTypes' => 1,
            'hasDictionaries' => 1,
            'UTFRangeStart' => '0061',
            'UTFRangeEnd' => '007A',
            'righttoleft' => 0,
        ],
        [
            'id' => 2,
            'name' => 'French',
            'subdomain' => 'fr',
            'i18nspec' => 'fr',
            'hasOrigins' => 1,
            'hasRegions' => 1,
            'hasTypes' => 1,
            'hasDictionaries' => 1,
            'UTFRangeStart' => '0061',
            'UTFRangeEnd' => '007A',
            'righttoleft' => 0,
        ],
        [
            'id' => 7,
            'name' => 'LJL',
            'subdomain' => 'ljl',
            'i18nspec' => 'en',
            'hasOrigins' => 1,
            'hasRegions' => 1,
            'hasTypes' => 1,
            'hasDictionaries' => 1,
            'UTFRangeStart' => '0061',
            'UTFRangeEnd' => '007A',
            'righttoleft' => 0,
        ],
    ];
}