<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class DictionariesFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false],
        'dictionary' => ['type' => 'string', 'length' => 255, 'null' => false],
        'language_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    public array $records = [
        ['id' => 1, 'dictionary' => 'Main', 'language_id' => 1],
    ];
}