<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class RemoveLanguageidFromOrigins extends AbstractMigration
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
        $table = $this->table('origins');
        $table->removeColumn('language_id');
        $table->update();
    }
}
