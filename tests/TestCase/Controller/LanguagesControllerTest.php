<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class LanguagesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Languages',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->configRequest([
            'headers' => ['Host' => 'en.example.test'],
        ]);
    }

    public function testAboutRenders()
    {
        $this->get('/about');
        $this->assertResponseOk();
        $this->assertResponseContains('Enter the Lexicon');
    }

    public function testNotesRenders()
    {
        $this->get('/notes');
        $this->assertResponseOk();
        $this->assertResponseContains('section id="main"');
    }
}
