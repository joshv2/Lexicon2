<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddHasTypeCategoriesToLanguages extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('languages');
        $table->addColumn('hasTypeCategories', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
