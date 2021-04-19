<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PronunciationsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PronunciationsTable Test Case
 */
class PronunciationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PronunciationsTable
     */
    protected $Pronunciations;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Pronunciations',
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
        $config = $this->getTableLocator()->exists('Pronunciations') ? [] : ['className' => PronunciationsTable::class];
        $this->Pronunciations = $this->getTableLocator()->get('Pronunciations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Pronunciations);

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
