<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddAnalyticsoldToLanguages extends AbstractMigration
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
        $table->addColumn('googleAnalyticsOld', 'string', [
            'default' => null,
            'limit' => 15,
            'null' => false,
        ]);
        $table->update();
    }
}
