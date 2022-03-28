<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AlterApprovedOnSentenceRecordings extends AbstractMigration
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
        $table->changeColumn('approved', 'tinyinteger', [
            'default' => null,
            'limit' => 4,
            'null' => false,
        ]);
        $table->update();
    }
}
