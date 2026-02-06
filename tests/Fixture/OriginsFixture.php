<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class OriginsFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'autoIncrement' => true],
        'word_id' => ['type' => 'integer', 'length' => 11, 'null' => true],
        'language_id' => ['type' => 'integer', 'length' => 11, 'null' => true],
        'origin' => ['type' => 'string', 'length' => 255, 'null' => false],
        'source' => ['type' => 'string', 'length' => 255, 'null' => true],
        'notes' => ['type' => 'text', 'null' => true],
        'created' => ['type' => 'datetime', 'null' => true],
        'modified' => ['type' => 'datetime', 'null' => true],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
        '_indexes' => [
            'word_idx' => ['type' => 'index', 'columns' => ['word_id']],
            'lang_idx' => ['type' => 'index', 'columns' => ['language_id']],
        ],
    ];

    public array $records = [
        [
            'id' => 1,
            'word_id' => 1,
            'language_id' => 1,
            'origin' => 'Old English',
            'source' => 'OED',
            'notes' => 'Borrowed into Middle English from Old English forms.',
            'created' => '2025-01-01 00:00:00',
            'modified' => '2025-01-01 00:00:00',
        ],
        [
            'id' => 2,
            'word_id' => 2,
            'language_id' => 2,
            'origin' => 'Latin',
            'source' => 'Wiktionary',
            'notes' => 'Derived from Classical Latin root.',
            'created' => '2025-01-02 00:00:00',
            'modified' => '2025-01-02 00:00:00',
        ],
    ];
}