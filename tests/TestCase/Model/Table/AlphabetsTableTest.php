<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AlphabetsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AlphabetsTable Test Case
 */
class AlphabetsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AlphabetsTable
     */
    protected $Alphabets;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Alphabets',
        'app.Languages',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Alphabets') ? [] : ['className' => AlphabetsTable::class];
        $this->Alphabets = $this->getTableLocator()->get('Alphabets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Alphabets);

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
