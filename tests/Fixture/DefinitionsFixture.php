<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class DefinitionsFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false],
        'word_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'definition' => ['type' => 'text', 'null' => false],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    public array $records = [
        ['id' => 1, 'word_id' => 1, 'definition' => 'A fruit that grows on trees.'],
        ['id' => 2, 'word_id' => 2, 'definition' => 'Le fruit de l\'arbre.'],
    ];
}