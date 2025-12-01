<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'char', 'length' => 36, 'null' => false],
        'username' => ['type' => 'string', 'length' => 255, 'null' => false],
        'email' => ['type' => 'string', 'length' => 255, 'null' => true],
        'password' => ['type' => 'string', 'length' => 255, 'null' => false],
        'created' => ['type' => 'datetime', 'null' => true],
        'modified' => ['type' => 'datetime', 'null' => true],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    public array $records = [
        [
            'id' => '00000000-0000-0000-0000-000000000001',
            'username' => 'admin',
            'email' => 'admin@example.test',
            'password' => 'passwordhash',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ],
    ];
}