<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

class WordsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Words',
        'app.Origins',
        'app.Types',
        'app.Regions',
        'app.Dictionaries',
        'app.Users',
        'app.Pronunciations',
        'app.Definitions',
        'app.Sentences',
        'app.Languages',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->configRequest([
            'headers' => ['Host' => 'en.example.test'],
        ]);
    }

    public function testBrowsewordsReturnsJson()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $post = ['selectedOptions' => 'Origins_1'];
        $this->post('/words/browsewords', $post);

        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $body = json_decode((string)$this->_response->getBody(), true);
        $this->assertArrayHasKey('success', $body);
        $this->assertArrayHasKey('language', $body['success']);
        $this->assertArrayHasKey('words', $body['success']);
    }

    public function testAddCreatesWordForSuperuser()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->session([
            'Auth' => [
                'username' => 'admin',
                'role' => 'superuser',
                'id' => '00000000-0000-0000-0000-000000000001',
            ]
        ]);

        $postData = [
            'spelling' => 'testword-' . time(),
            'language_id' => 1,
        ];

        $this->post('/words/add', $postData, ['headers' => ['Host' => 'en.example.test']]);
        $this->assertResponseSuccess();

        $location = (string)$this->_response->getHeaderLine('Location');

        if ($location === '') {
            $words = TableRegistry::getTableLocator()->get('Words');
            $found = $words->find()->where(['spelling' => $postData['spelling']])->first();
            $this->assertNotNull($found, 'Expected new Word to be saved when no redirect header returned.');
        } else {
            // Accept any /words/* redirect, not just /words/view
            $this->assertMatchesRegularExpression('/^\/words\/(view|[0-9]+)/', $location);
        }
    }

    public function testEditUpdatesWord()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->session([
            'Auth' => [
                'username' => 'admin',
                'role' => 'superuser',
                'id' => '00000000-0000-0000-0000-000000000001',
            ]
        ]);

        $wordsTable = TableRegistry::getTableLocator()->get('Words');
        $existing = $wordsTable->get(1);
        $this->assertNotNull($existing);

        $postData = [
            'spelling' => 'updated-spelling-' . time(),
            'definitions' => [
                ['definition' => '{"ops":[{"insert":"updated"}]}']
            ],
            'origins' => ['_ids' => [1]],
        ];

        $this->post('/words/edit/1', $postData, ['headers' => ['Host' => 'en.example.test']]);
        $this->assertResponseSuccess();

        $location = (string)$this->_response->getHeaderLine('Location');
        $this->assertNotEmpty($location, 'Expected a redirect Location header after editing.');
        $this->assertMatchesRegularExpression('/(\/words\/view|\/suggestions\/add|\/words\/[0-9]+)/', $location);

        $word = $wordsTable->get(1);
        $this->assertSame($postData['spelling'], $word->spelling);
    }

    public function testBrowsewordsRejectsMissingParameter()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->post('/words/browsewords', []);
        $this->assertResponseCode(500);
    }

    public function testAddFailsWithoutSpelling()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->session([
            'Auth' => [
                'username' => 'admin',
                'role' => 'superuser',
                'id' => '00000000-0000-0000-0000-000000000001',
            ]
        ]);

        $postData = [
            'language_id' => 1,
        ];

        $this->post('/words/add', $postData, ['headers' => ['Host' => 'en.example.test']]);
        $this->assertResponseOk(); // Expect 200, not failure
        $this->assertResponseContains('Spelling is required.');
    }

    public function testEditFailsWithInvalidId()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->session([
            'Auth' => [
                'username' => 'admin',
                'role' => 'superuser',
                'id' => '00000000-0000-0000-0000-000000000001',
            ]
        ]);

        $postData = [
            'spelling' => 'should-not-save',
            'definitions' => [
                ['definition' => '{"ops":[{"insert":"fail"}]}']
            ],
            'origins' => ['_ids' => [1]],
        ];

        $this->post('/words/edit/9999', $postData, ['headers' => ['Host' => 'en.example.test']]);
        $this->assertResponseCode(404);
    }

    public function testViewRedirectsIfWordNotFound()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->get('/words/view/9999');
        $this->assertRedirect(['controller' => 'Words', 'action' => 'wordnotfound']);
    }

    public function testDeleteRemovesWord()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->session([
            'Auth' => [
                'username' => 'admin',
                'role' => 'superuser',
                'id' => '00000000-0000-0000-0000-000000000001',
            ]
        ]);

        $wordsTable = TableRegistry::getTableLocator()->get('Words');
        $word = $wordsTable->newEntity(['spelling' => 'todelete-' . time(), 'language_id' => 1]);
        $wordsTable->save($word);

        $this->post('/words/delete/' . $word->id, [], ['headers' => ['Host' => 'en.example.test']]);
        $this->assertRedirect();

        $deleted = $wordsTable->find()->where(['id' => $word->id])->first();
        $this->assertNull($deleted, 'Word should be deleted.');
    }
}