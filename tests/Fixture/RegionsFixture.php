<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class RegionsFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'autoIncrement' => true],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false],
        'code' => ['type' => 'string', 'length' => 10, 'null' => true],
        'country_id' => ['type' => 'integer', 'length' => 11, 'null' => true],
        'created' => ['type' => 'datetime', 'null' => true],
        'modified' => ['type' => 'datetime', 'null' => true],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ],
        '_indexes' => [
            'country_idx' => ['type' => 'index', 'columns' => ['country_id']],
        ],
    ];

    public array $records = [
        [
            'id' => 1,
            'name' => 'North America',
            'code' => 'NA',
            'country_id' => null,
            'created' => '2025-01-01 00:00:00',
            'modified' => '2025-01-01 00:00:00',
        ],
        [
            'id' => 2,
            'name' => 'Europe',
            'code' => 'EU',
            'country_id' => null,
            'created' => '2025-01-02 00:00:00',
            'modified' => '2025-01-02 00:00:00',
        ],
    ];
}
