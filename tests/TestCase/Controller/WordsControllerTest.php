<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\WordsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\WordsController Test Case
 *
 * @uses \App\Controller\WordsController
 */
class WordsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Words',
        'app.Languages',
        'app.Alternates',
        'app.Definitions',
        'app.Sentences',
        'app.Pronunciations',
        'app.Dictionaries',
        'app.Origins',
        'app.Regions',
        'app.Types',
        'app.DictionariesWords',
        'app.OriginsWords',
        'app.RegionsWords',
        'app.TypesWords',
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
