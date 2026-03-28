<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class RegionsWordsFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'null' => false, 'autoIncrement' => true],
        'word_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'region_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
        '_indexes' => [
            'word_id' => ['type' => 'index', 'columns' => ['word_id']],
            'region_id' => ['type' => 'index', 'columns' => ['region_id']],
        ],
    ];

    public array $records = [
        ['id' => 1, 'word_id' => 1, 'region_id' => 1],
    ];
}
