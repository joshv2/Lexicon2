<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TypesWordsFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'null' => false, 'autoIncrement' => true],
        'word_id' => ['type' => 'integer', 'length' => 11, 'null' => true, 'default' => null],
        'type_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
        '_indexes' => [
            'word_id' => ['type' => 'index', 'columns' => ['word_id']],
            'type_id' => ['type' => 'index', 'columns' => ['type_id']],
        ],
    ];

    public array $records = [
        ['id' => 1, 'word_id' => 1, 'type_id' => 1],
    ];
}
