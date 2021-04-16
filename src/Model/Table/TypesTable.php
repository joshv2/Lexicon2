<?php
// src/Model/Table/TypesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class TypesTable extends Table
{
    public function initialize(array $config): void
    {
        //$this->addBehavior('Timestamp');
        $this->belongsToMany('Words', ['joinTable' => 'words_types']);
    }

    public function top_types(){
        $query = $this->find('list', ['valueField' => 'type', 'limit' => 15, 'order' => 'id']);
        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }
}