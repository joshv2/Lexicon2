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
        'app.Alternates',
        'app.Origins',
        'app.OriginsWords',
        'app.Types',
        'app.TypesWords',
        'app.Regions',
        'app.RegionsWords',
        'app.Dictionaries',
        'app.DictionariesWords',
        'app.Users',
        'app.Pronunciations',
        'app.Definitions',
        'app.Sentences',
        'app.SentenceRecordings',
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

    public function testViewRejectsUnapprovedWordWhenLoggedOut()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // WordsFixture has id=3 as approved=0
        $this->get('/words/view/3');
        $this->assertRedirect(['controller' => 'Words', 'action' => 'wordnotfound']);
    }

    public function testViewAllowsUnapprovedWordWhenLoggedIn()
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

        $this->get('/words/view/3');
        $this->assertResponseOk();
        $this->assertResponseContains('pendingword');
    }

    public function testApproveWordApprovesPendingPronunciationsAndSentenceRecordings(): void
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

        $words = TableRegistry::getTableLocator()->get('Words');
        $pronunciations = TableRegistry::getTableLocator()->get('Pronunciations');
        $sentences = TableRegistry::getTableLocator()->get('Sentences');
        $sentenceRecordings = TableRegistry::getTableLocator()->get('SentenceRecordings');

        // Word 3 is pending in fixture.
        $this->assertSame(0, (int)$words->get(3)->approved);

        $pendingPron = $pronunciations->newEntity([
            'word_id' => 3,
            'spelling' => 'pendingword',
            'sound_file' => null,
            'pronunciation' => '/pɛn/',
            'approved' => 0,
        ]);
        $this->assertNotFalse($pronunciations->save($pendingPron));

        $deniedPron = $pronunciations->newEntity([
            'word_id' => 3,
            'spelling' => 'pendingword',
            'sound_file' => null,
            'pronunciation' => '/dɪˈnaɪd/',
            'approved' => -1,
        ]);
        $this->assertNotFalse($pronunciations->save($deniedPron));

        $sentence = $sentences->newEntity([
            'word_id' => 3,
            'sentence' => 'This is a pending word.',
        ]);
        $this->assertNotFalse($sentences->save($sentence));

        $pendingRecording = $sentenceRecordings->newEntity([
            'sentence_id' => $sentence->id,
            'sound_file' => null,
            'approved' => 0,
        ]);
        $this->assertNotFalse($sentenceRecordings->save($pendingRecording));

        $deniedRecording = $sentenceRecordings->newEntity([
            'sentence_id' => $sentence->id,
            'sound_file' => null,
            'approved' => -1,
        ]);
        $this->assertNotFalse($sentenceRecordings->save($deniedRecording));

        $this->post('/words/approve/3', []);
        $this->assertRedirect();

        $wordAfter = $words->get(3);
        $this->assertSame(1, (int)$wordAfter->approved);

        $pendingPronAfter = $pronunciations->get($pendingPron->id);
        $this->assertSame(1, (int)$pendingPronAfter->approved);
        $this->assertSame('00000000-0000-0000-0000-000000000001', (string)$pendingPronAfter->approving_user_id);

        $deniedPronAfter = $pronunciations->get($deniedPron->id);
        $this->assertSame(-1, (int)$deniedPronAfter->approved);

        $pendingRecAfter = $sentenceRecordings->get($pendingRecording->id);
        $this->assertSame(1, (int)$pendingRecAfter->approved);
        $this->assertSame('00000000-0000-0000-0000-000000000001', (string)$pendingRecAfter->approving_user_id);

        $deniedRecAfter = $sentenceRecordings->get($deniedRecording->id);
        $this->assertSame(-1, (int)$deniedRecAfter->approved);
    }

    public function testIndexRendersBrowsePage()
    {
        $this->get('/words');
        $this->assertResponseOk();
        $this->assertResponseContains('Add a new word');
    }

    public function testIndexShowsErrorForUnknownQueryParam()
    {
        $this->get('/words?hack=1');
        $this->assertResponseOk();
        $this->assertResponseContains('You entered invalid query params');
    }

    public function testIndexShowsErrorForInvalidFilterValue()
    {
        $this->get('/words?origin=abc');
        $this->assertResponseOk();
        $this->assertResponseContains('You entered invalid query params');
    }

    public function testIndexOutOfBoundsPageShowsErrorMessage()
    {
        $this->get('/words?displayType=page&page=999');
        $this->assertResponseOk();
        $this->assertResponseContains('out of bounds');
    }

    public function testIndexPaginatedDisplayTypePageRenders()
    {
        $this->get('/words?displayType=page&page=1');
        $this->assertResponseOk();
        $this->assertResponseNotContains('You entered invalid query params');
    }

    public function testIndexDictionaryNoneFilterRenders()
    {
        $this->get('/words?dictionary=none');
        $this->assertResponseOk();
        $this->assertResponseNotContains('You entered invalid query params');
    }

    public function testIndexRejectsNonNumericPageParameter()
    {
        $this->get('/words?displayType=page&page=abc');
        $this->assertResponseOk();
        $this->assertResponseContains('You entered invalid query params');
    }

    public function testRandomRenders()
    {
        $this->get('/random');
        $this->assertResponseOk();
        $this->assertResponseContains('random words retrieved');
    }

    public function testAlphabeticalRenders()
    {
        $this->get('/alphabetical');

        if ($this->_response->getStatusCode() >= 500) {
            $this->fail("Unexpected 5xx response. Body:\n" . (string)$this->_response->getBody());
        }

        $this->assertResponseOk();
        $this->assertResponseContains('Showing');
    }

    public function testAlphabeticalLetterRouteRenders()
    {
        $this->get('/alphabetical/b');
        $this->assertResponseOk();
        $this->assertResponseContains('Showing');
    }

    public function testWordNotFoundPageRenders()
    {
        $this->get('/words/wordnotfound');
        $this->assertResponseOk();
        $this->assertResponseContains('This word can not be found');
    }

    public function testSuccessPageRenders()
    {
        $this->get('/words/success');
        $this->assertResponseOk();
        $this->assertResponseContains('Thank you');
    }

    public function testAddProcessesQuillJsonAndApprovesPronunciationsForSuperuser()
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

        $spelling = 'quilltest-' . time();

        $postData = [
            'spelling' => $spelling,
            'language_id' => 1,

            // Quill JSON field processing
            // Invalid JSON exercises the "invalid JSON" branch and should not crash
            'etymology' => 'not-json',
            'notes' => '{"ops":[{"insert":"some notes"}]}' ,

            // Quill association processing
            'definitions' => [
                ['definition' => '{"ops":[{"insert":"a definition"}]}'],
            ],
            // Blank delta should be filtered out
            'sentences' => [
                ['sentence' => '{"ops":[{"insert":"\n"}]}'],
            ],

            // Empty association should be removed by filterAssociations()
            'alternates' => [
                ['spelling' => ''],
            ],

            // Non-empty pronunciation should survive filterAssociations() and be auto-approved for superusers
            'pronunciations' => [
                [
                    'pronunciation' => '/t/',
                    'sound_file' => '',
                    'spelling' => '',
                ],
            ],
        ];

        $this->post('/words/add', $postData, ['headers' => ['Host' => 'en.example.test']]);
        $this->assertResponseSuccess();
        $this->assertRedirect();

        $wordsTable = TableRegistry::getTableLocator()->get('Words');
        $saved = $wordsTable->find()->where(['spelling' => $spelling])->contain(['Pronunciations'])->first();
        $this->assertNotNull($saved, 'Expected word to be saved.');
        $this->assertNotEmpty($saved->pronunciations, 'Expected associated pronunciations to be saved.');
        $this->assertSame(1, (int)$saved->pronunciations[0]->approved, 'Expected superuser pronunciations to be auto-approved.');
    }

    public function testBrowsewords2ReturnsJson()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $post = ['requestedWordIds' => [1, 2]];
        $this->post('/words/browsewords2', $post);

        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $body = json_decode((string)$this->_response->getBody(), true);
        $this->assertArrayHasKey('success', $body);
        $this->assertArrayHasKey('language', $body['success']);
        $this->assertArrayHasKey('words', $body['success']);
    }

    public function testBrowsewords2EmptyListReturnsEmptyWords()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $post = ['requestedWordIds' => []];
        $this->post('/words/browsewords2', $post);

        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $body = json_decode((string)$this->_response->getBody(), true);
        $this->assertArrayHasKey('success', $body);
        $this->assertSame('[]', $body['success']['words']);
    }

    public function testCheckForWordReturnsJson()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $post = ['spelling' => 'apple', 'language_id' => 1];
        $this->post('/words/checkforword', $post);

        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $body = json_decode((string)$this->_response->getBody(), true);
        $this->assertArrayHasKey('spelling', $body);
        $this->assertFalse($body['spelling'], 'Expected spelling=false for an existing approved word.');
    }

    public function testCheckForWordGetReturnsSuccess0()
    {
        $this->get('/words/checkforword');

        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $body = json_decode((string)$this->_response->getBody(), true);
        $this->assertSame(0, $body['success']);
    }

    public function testBrowsewordsGetReturns404()
    {
        $this->get('/words/browsewords');
        $this->assertResponseCode(404);
    }

    public function testEditRedirectsToSuggestionsWhenLoggedOut()
    {
        $this->get('/words/edit/1');
        $this->assertRedirectContains('/suggestions/add/1');
    }

    public function testApproveApprovesWordAndPronunciations()
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
        $pronunciationsTable = TableRegistry::getTableLocator()->get('Pronunciations');

        $word = $wordsTable->newEntity([
            'spelling' => 'toapprove-' . time(),
            'language_id' => 1,
            'approved' => 0,
            'user_id' => '00000000-0000-0000-0000-000000000001',
        ]);
        $savedWord = $wordsTable->save($word);
        $this->assertNotFalse($savedWord);

        $p = $pronunciationsTable->newEntity([
            'word_id' => $savedWord->id,
            'spelling' => $savedWord->spelling,
            // Empty sound_file prevents calling ProcessFile::converttomp3()
            'sound_file' => '',
            'pronunciation' => '/x/',
            'approved' => 0,
            'display_order' => 1,
        ]);
        $savedP = $pronunciationsTable->save($p);
        $this->assertNotFalse($savedP);

        $this->post('/words/approve/' . $savedWord->id);
        $this->assertRedirect();

        $reloaded = $wordsTable->get($savedWord->id, contain: ['Pronunciations']);
        $this->assertSame(1, (int)$reloaded->approved);
        $this->assertNotEmpty($reloaded->pronunciations);
        $this->assertSame(1, (int)$reloaded->pronunciations[0]->approved);
    }

    public function testSubdomainWordIdMiddlewareRedirectsWhenInRange()
    {
        $this->configRequest([
            'environment' => ['HTTP_HOST' => 'ljl.example.test'],
        ]);

        $this->get('/words/1');
        $this->assertResponseCode(302);
        $this->assertHeader('Location', '/words/10');
    }

    public function testViewRendersQuillLinksInDefinitions()
    {
        $definitionsTable = TableRegistry::getTableLocator()->get('Definitions');

        $definitionHtml = '<p>According to <a href="https://jel.jewish-languages.org/words/250">kashrut</a>.<br /></p>';
        $entity = $definitionsTable->newEntity([
            'id' => 99,
            'word_id' => 1,
            'definition' => $definitionHtml,
        ]);
        $saved = $definitionsTable->save($entity);
        $this->assertNotFalse($saved, 'Expected definition to be saved for rendering test.');

        $this->get('/words/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('href="https://jel.jewish-languages.org/words/250"');
        $this->assertResponseContains('>kashrut<');
        $this->assertResponseNotContains('&lt;a');
    }
}