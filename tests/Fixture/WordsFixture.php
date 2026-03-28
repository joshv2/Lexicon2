<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class WordsFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'autoIncrement' => true],
        'old_id' => ['type' => 'integer', 'length' => 11, 'null' => true, 'default' => null],
        'spelling' => ['type' => 'string', 'length' => 255, 'null' => false],
        'etymology' => ['type' => 'text', 'null' => true, 'default' => null],
        'etymology_json' => ['type' => 'text', 'null' => true, 'default' => null],
        'notes' => ['type' => 'text', 'null' => true, 'default' => null],
        'notes_json' => ['type' => 'text', 'null' => true, 'default' => null],
        'language_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'created' => ['type' => 'timestamp', 'null' => true, 'default' => null],
        'approved' => ['type' => 'boolean', 'null' => true, 'default' => null],
        'approved_date' => ['type' => 'timestamp', 'null' => true, 'default' => null],
        'user_id' => ['type' => 'char', 'length' => 36, 'null' => true, 'default' => null],
        'full_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null],
        'email' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    public array $records = [
        ['id' => 1, 'spelling' => 'apple', 'language_id' => 1, 'created' => '2025-01-01 00:00:00', 'approved' => 1],
        ['id' => 4, 'spelling' => 'banana', 'notes' => 'noteonly: this word is only findable via notes', 'language_id' => 1, 'created' => '2025-01-01 00:00:01', 'approved' => 1],
        ['id' => 2, 'spelling' => 'pomme', 'language_id' => 2, 'created' => '2025-01-02 00:00:00', 'approved' => 1],
        ['id' => 3, 'spelling' => 'pendingword', 'language_id' => 1, 'created' => '2025-01-03 00:00:00', 'approved' => 0],
        ['id' => 10, 'old_id' => 1, 'spelling' => 'redirectedword', 'language_id' => 7, 'created' => '2025-01-04 00:00:00', 'approved' => 1],
    ];
}