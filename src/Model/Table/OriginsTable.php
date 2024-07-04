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
        $this->belongsToMany('Words'); #, ['joinTable' => 'words_origins']
        $this->belongsToMany('Languages', [
            'through' => 'OriginsLanguages'
        ]);
    }

    public function top_origins_for_home($langid){
        $query = $this->find('list', valueField: 'origin', order: 'Origins.id')
                        //->contain(['Languages'])
                        ->matching('Languages')
                        ->where(['OriginsLanguages.top' => 1, 'OriginsLanguages.language_id' => $langid]);
        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }


    public function top_origins($langid){
        $query = $this->find('list', ['valueField' => 'origin', 'order' => 'Origins.id'])
                        //->contain(['Languages'])
                        ->matching('Languages')
                        ->where(['OriginsLanguages.top' => 1, 'OriginsLanguages.language_id' => $langid]);

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

    public function get_region_by_name($origin){
        $query = $this->find()->where(['origin' => $origin]);
        $nresults = $query->count();
        if ($nresults == 0) {
            $idarray = [];
        } else {
            foreach ($query as $q){
                $idarray[] = $q->id;
            }
        }
        return $idarray;
    }

}