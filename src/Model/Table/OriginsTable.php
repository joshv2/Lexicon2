<?php
// src/Model/Table/OriginsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class OriginsTable extends Table
{
    public function initialize(array $config): void
    {
        //$this->addBehavior('Timestamp');
        $this->belongsToMany('Words', ['joinTable' => 'words_origins']);
    }

    public function top_origins(){
        $query = $this->find('list', ['valueField' => 'origin', 'limit' => 7, 'order' => 'id']);
        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }
}