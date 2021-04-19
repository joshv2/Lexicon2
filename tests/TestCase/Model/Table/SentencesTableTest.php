<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SentencesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SentencesTable Test Case
 */
class SentencesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SentencesTable
     */
    protected $Sentences;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Sentences',
        'app.Words',
        'app.SentenceRecordings',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Sentences') ? [] : ['className' => SentencesTable::class];
        $this->Sentences = $this->getTableLocator()->get('Sentences', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Sentences);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
