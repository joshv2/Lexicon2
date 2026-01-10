<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class SentencesFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false],
        'word_id' => ['type' => 'integer', 'length' => 11, 'null' => false],
        'sentence' => ['type' => 'text', 'null' => true],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    public array $records = [
        ['id' => 1, 'word_id' => 1, 'sentence' => 'I ate an apple.'],
    ];
}