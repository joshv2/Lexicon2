<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class SentencesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Words',
        'app.Sentences',
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

    public function testViewRendersQuillLinkInSentence(): void
    {
        $this->session([
            'Auth' => [
                'username' => 'admin',
                'role' => 'admin',
                'id' => '00000000-0000-0000-0000-000000000001',
            ]
        ]);

        $sentencesTable = TableRegistry::getTableLocator()->get('Sentences');

        $sentenceHtml = '<p>See <a href="https://jel.jewish-languages.org/words/250">kashrut</a>.<br /></p>';
        $entity = $sentencesTable->newEntity([
            'word_id' => 1,
            'sentence' => $sentenceHtml,
            'sentence_json' => null,
        ]);
        $saved = $sentencesTable->save($entity);
        $this->assertNotFalse($saved, 'Expected sentence to be saved for rendering test.');

        $this->get('/sentences/view/' . $saved->id);
        $this->assertResponseOk();
        $this->assertResponseContains('href="https://jel.jewish-languages.org/words/250"');
        $this->assertResponseContains('>kashrut<');
        $this->assertResponseNotContains('&lt;a');
    }
}
