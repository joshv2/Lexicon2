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
        $this->belongsToMany('Users', ['className' => 'CakeDC/Users.Users']);
        $this->belongsToMany('Languages', [
            'through' => 'TypesLanguages'
        ]);
    }

    public function top_types_for_home($langid){
        $query = $this->find('list', ['valueField' => 'type', 'order' => 'Types.id'])
                        ->contain(['Languages'])
                        ->matching('Languages')
                        ->where(['TypesLanguages.top' => 1, 'TypesLanguages.language_id' => $langid]);
        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }

    public function top_types_for_registration($langid){
        $query = $this->find('list', ['valueField' => 'type', 'order' => 'Types.id'])
                        ->contain(['Languages'])
                        ->matching('Languages')
                        ->where(['TypesLanguages.top' => 1, 'TypesLanguages.language_id' => $langid]);
        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }

    public function top_types($langid){
        $query = $this->find('list', ['valueField' => 'type', 'order' => 'Types.id'])
                        ->contain(['Languages'])
                        ->matching('Languages')
                        ->where(['TypesLanguages.top' => 1, 'TypesLanguages.language_id' => $langid]);
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