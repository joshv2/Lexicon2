<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class TypesFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false],
        'type' => ['type' => 'string', 'length' => 255, 'null' => true],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    public array $records = [
        ['id' => 1, 'type' => 'noun'],
        ['id' => 2, 'type' => 'verb'],
    ];
}