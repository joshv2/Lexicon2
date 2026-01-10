<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PronunciationsFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false],
        'word_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'spelling' => ['type' => 'string', 'length' => 255, 'null' => false],
        'pronunciation' => ['type' => 'string', 'length' => 4000, 'null' => false],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    public array $records = [
        ['id' => 1, 'word_id' => 1, 'spelling' => 'apple', 'pronunciation' => '/ˈæpəl/'],
    ];
}