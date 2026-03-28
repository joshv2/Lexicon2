<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class DefinitionsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Words',
        'app.Definitions',
        'app.Languages',
        'app.Users',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->configRequest([
            'headers' => ['Host' => 'en.example.test'],
        ]);
    }

    public function testViewRendersQuillLinkInDefinition(): void
    {
        $this->session([
            'Auth' => [
                'username' => 'admin',
                'role' => 'admin',
                'id' => '00000000-0000-0000-0000-000000000001',
            ]
        ]);

        $definitionsTable = TableRegistry::getTableLocator()->get('Definitions');

        $definitionHtml = '<p>According to <a href="https://jel.jewish-languages.org/words/250">kashrut</a>.<br /></p>';
        $entity = $definitionsTable->newEntity([
            'word_id' => 1,
            'definition' => $definitionHtml,
        ]);
        $saved = $definitionsTable->save($entity);
        $this->assertNotFalse($saved, 'Expected definition to be saved for rendering test.');

        $this->get('/definitions/view/' . $saved->id);
        $this->assertResponseOk();
        $this->assertResponseContains('href="https://jel.jewish-languages.org/words/250"');
        $this->assertResponseContains('>kashrut<');
        $this->assertResponseNotContains('&lt;a');
    }
}
