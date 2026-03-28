<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class SentenceRecordingsFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'autoIncrement' => true],
        'sentence_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'sound_file' => ['type' => 'string', 'length' => 4000, 'null' => true, 'default' => null],
        'display_order' => ['type' => 'integer', 'length' => 11, 'null' => true, 'default' => null],
        'notes' => ['type' => 'text', 'null' => true, 'default' => null],
        'approved' => ['type' => 'integer', 'length' => 4, 'null' => true, 'default' => null],
        'approved_date' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'user_id' => ['type' => 'char', 'length' => 36, 'null' => true, 'default' => null],
        'approving_user_id' => ['type' => 'char', 'length' => 36, 'null' => true, 'default' => null],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
        '_indexes' => [
            'sentence_id' => ['type' => 'index', 'columns' => ['sentence_id']],
        ],
    ];

    public array $records = [
        // Intentionally empty for most tests.
    ];
}
