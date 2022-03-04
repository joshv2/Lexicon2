<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateTypesUsers extends AbstractMigration
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
        $table = $this->table('types_users');
        $table->addColumn('user_id', 'char', [
            'default' => null,
            'limit' => 36,
            'null' => false,
        ]);
        $table->addColumn('type_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->create();
    }
}
