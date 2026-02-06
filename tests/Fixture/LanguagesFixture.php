<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class LanguagesFixture extends TestFixture
{
    public $import = false;

    public array $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false],
        'name' => ['type' => 'string', 'length' => 255, 'null' => true],
        'subdomain' => ['type' => 'string', 'length' => 255, 'null' => true],
        'i18nspec' => ['type' => 'string', 'length' => 6, 'null' => true],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    public array $records = [
        ['id' => 1, 'name' => 'English', 'subdomain' => 'en', 'i18nspec' => 'en'],
        ['id' => 2, 'name' => 'French', 'subdomain' => 'fr', 'i18nspec' => 'fr'],
    ];
}