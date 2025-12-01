<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class WordsFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false],
        'spelling' => ['type' => 'string', 'length' => 255, 'null' => false],
        'language_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'created' => ['type' => 'timestamp', 'null' => true, 'default' => null],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    public array $records = [
        ['id' => 1, 'spelling' => 'apple', 'language_id' => 1, 'created' => '2025-01-01 00:00:00'],
        ['id' => 2, 'spelling' => 'pomme', 'language_id' => 2, 'created' => '2025-01-02 00:00:00'],
    ];
}