<?php
// src/Model/Table/RegionsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class OriginsLanguagesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->belongsTo('Origins');
        $this->belongsTo('Languages');
    }
}