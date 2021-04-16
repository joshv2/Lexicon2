<?php
// src/Model/Table/DictionariesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class DictionariesTable extends Table
{
    public function initialize(array $config): void
    {
        //$this->addBehavior('Timestamp');
        $this->belongsToMany('Words', ['joinTable' => 'words_dictionaries']);
    }

    public function top_dictionaries(){
        $query = $this->find('list', ['valueField' => 'dictionary', 'limit' => 6, 'order' => 'id']);
        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }
}