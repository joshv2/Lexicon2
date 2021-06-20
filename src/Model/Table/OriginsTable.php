<?php
// src/Model/Table/OriginsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class OriginsTable extends Table
{
    public function initialize(array $config): void
    {
        //$this->addBehavior('Timestamp');
        $this->setDisplayField('origin');
        $this->setPrimaryKey('id');
        $this->belongsToMany('Words', ['joinTable' => 'words_origins']);
    }

    public function top_origins(){
        $query = $this->find('list', ['valueField' => 'origin', 'limit' => 7, 'order' => 'id']);
        $query2 = $this->find('list', ['valueField' => 'origin'])
                        ->where(['id' => 999]);
        $query = $query->union($query2);
        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }

    public function get_all_ids(){
        $query = $this->find()->all()->extract('id');
        foreach ($query as $q){
            $idarray[] = $q;
        }
        $typeids = implode(',',$idarray);
        return $typeids;
    }
}