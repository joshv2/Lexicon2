<?php
// src/Model/Table/RegionsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class RegionsTable extends Table
{
    public function initialize(array $config): void
    {
        //$this->addBehavior('Timestamp');
        $this->setDisplayField('region');
        $this->setPrimaryKey('id');
        $this->belongsToMany('Words');
        $this->belongsToMany('Languages', [
            'through' => 'RegionsLanguages'
        ]);
    }


    public function top_regions_for_home($langid){
        $query = $this->find('list', 
                            valueField: 'region', 
                            order: ['Regions.id' => 'ASC'])
                                //->contain(['Languages'])
                                ->matching('Languages')
                                ->where(['RegionsLanguages.top' => 1, 'RegionsLanguages.language_id' => $langid]);

        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }

    public function top_regions($langid){
        $query =$this->find('list', 
                        valueField: 'region', 
                        order: ['Regions.id' => 'ASC'])
                        //->contain(['Languages'])
                        ->matching('Languages')
                        ->where(['RegionsLanguages.top' => 1, 'RegionsLanguages.language_id' => $langid]);
        $query2 = $this->find(type: 'list', options: ['valueField' => 'region'])
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