<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class OriginsWordsFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'null' => false, 'autoIncrement' => true],
        'word_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'origin_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
        '_indexes' => [
            'word_id' => ['type' => 'index', 'columns' => ['word_id']],
            'origin_id' => ['type' => 'index', 'columns' => ['origin_id']],
        ],
    ];

    public array $records = [
        ['id' => 1, 'word_id' => 1, 'origin_id' => 1],
    ];
}
