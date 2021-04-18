<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DefinitionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DefinitionsTable Test Case
 */
class DefinitionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DefinitionsTable
     */
    protected $Definitions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Definitions',
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
        $config = $this->getTableLocator()->exists('Definitions') ? [] : ['className' => DefinitionsTable::class];
        $this->Definitions = $this->getTableLocator()->get('Definitions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Definitions);

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
