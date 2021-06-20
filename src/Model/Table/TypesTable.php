<?php
// src/Model/Table/TypesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class TypesTable extends Table
{
    public function initialize(array $config): void
    {
        //$this->addBehavior('Timestamp');
        $this->setDisplayField('type');
        $this->setPrimaryKey('id');
        $this->belongsToMany('Words', ['joinTable' => 'words_types']);
    }

    public function top_types(){
        $query = $this->find('list', ['valueField' => 'type', 'limit' => 12, 'order' => 'id']);
        $query2 = $this->find('list', ['valueField' => 'type'])
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