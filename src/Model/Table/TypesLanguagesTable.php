<?php
// src/Model/Table/RegionsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class TypesLanguagesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->belongsTo('Types');
        $this->belongsTo('Languages');
        $this->belongsTo('TypeCategories');
    }
}