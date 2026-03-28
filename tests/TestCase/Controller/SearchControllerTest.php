<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class SearchControllerTest extends TestCase
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

    public function testSearchFindsApprovedWord()
    {
        $this->get('/search?q=apple');
        $this->assertResponseOk();
        $this->assertResponseContains('Your search for');
        $this->assertResponseContains('apple');
    }

    public function testSearchEmptyQueryShowsNoResultsMessage()
    {
        $this->get('/search?q=');
        $this->assertResponseOk();
        $this->assertResponseContains('That word is not yet in the database');
    }

    public function testSearchDisplayAllOmitsDisplayAllButton()
    {
        $this->get('/search?q=apple&displayType=all');
        $this->assertResponseOk();
        $this->assertResponseContains('Your search for');
        $this->assertResponseContains('apple');
        $this->assertResponseNotContains('displayAllButton');
    }

    public function testSearchFallsBackToDefinitionMatches()
    {
        $this->get('/search?q=fruit');
        $this->assertResponseOk();
        $this->assertResponseContains('That word is not yet in the database');
        $this->assertResponseContains('Definitions:');
        $this->assertResponseContains('apple');
    }
}
