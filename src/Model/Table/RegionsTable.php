<?php
// src/Model/Table/RegionsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class RegionsTable extends Table
{
    public function initialize(array $config): void
    {
        //$this->addBehavior('Timestamp');
        $this->belongsToMany('Words', ['joinTable' => 'words_regions']);
    }

    public function top_regions(){
        $query = $this->find('list', ['valueField' => 'region', 'limit' => 4, 'order' => 'id']);
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