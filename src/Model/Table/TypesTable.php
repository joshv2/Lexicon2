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
        $this->belongsToMany('Words');
        $this->belongsTo('Languages', [
            'foreignKey' => 'language_id',
            'joinType' => 'INNER',
        ]);
    }

    public function top_types_for_home($langid){
        $query = $this->find('list', ['valueField' => 'type', 'order' => 'id'])
                        ->where(['top' => 1, 'language_id' => $langid]);
        $query3 = $this->find('list', ['valueField' => 'type'])
                        ->where(['id' => 998]); //chabad
        $query = $query->union($query3);
        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }

    public function top_types($langid){
        $query = $this->find('list', ['valueField' => 'type', 'order' => 'id'])
                        ->where(['top' => 1, 'language_id' => $langid]);
        $query3 = $this->find('list', ['valueField' => 'type'])
                        ->where(['id' => 998]); //chabad
        $query2 = $this->find('list', ['valueField' => 'type'])
                        ->where(['id' => 999]);
        $query = $query->union($query3);
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