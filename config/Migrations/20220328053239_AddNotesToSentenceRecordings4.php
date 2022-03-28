<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddNotesToSentenceRecordings4 extends AbstractMigration
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
        $table = $this->table('sentence_recordings');
        $table->addColumn('notes', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('approved', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('approved_date', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('user_id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('approving_user_id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
