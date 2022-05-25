<?php
// src/Model/Table/RegionsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class RegionsLanguagesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->belongsTo('Regions');
        $this->belongsTo('Languages');
    }
}