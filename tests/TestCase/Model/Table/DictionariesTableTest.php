<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DictionariesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DictionariesTable Test Case
 */
class DictionariesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DictionariesTable
     */
    protected $Dictionaries;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Dictionaries',
        'app.Words',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Dictionaries') ? [] : ['className' => DictionariesTable::class];
        $this->Dictionaries = $this->getTableLocator()->get('Dictionaries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Dictionaries);

        parent::tearDown();
    }

    /**
     * Test top_dictionaries method
     *
     * @return void
     */
    public function testTopDictionaries(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
