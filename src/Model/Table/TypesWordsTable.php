<?php
// src/Model/Table/TypesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class TypesWordsTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setPrimaryKey('id');
        $this->belongsToMany('Words');
    }
}