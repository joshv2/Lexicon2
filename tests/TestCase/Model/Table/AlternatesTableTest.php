<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AlternatesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AlternatesTable Test Case
 */
class AlternatesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AlternatesTable
     */
    protected $Alternates;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Alternates',
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
        $config = $this->getTableLocator()->exists('Alternates') ? [] : ['className' => AlternatesTable::class];
        $this->Alternates = $this->getTableLocator()->get('Alternates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Alternates);

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
