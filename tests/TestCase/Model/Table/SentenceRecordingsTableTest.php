<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SentenceRecordingsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SentenceRecordingsTable Test Case
 */
class SentenceRecordingsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SentenceRecordingsTable
     */
    protected $SentenceRecordings;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.SentenceRecordings',
        'app.Sentences',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SentenceRecordings') ? [] : ['className' => SentenceRecordingsTable::class];
        $this->SentenceRecordings = $this->getTableLocator()->get('SentenceRecordings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SentenceRecordings);

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
