<?php
// src/Model/Table/OriginsTable.php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Query\SelectQuery;
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


    public function findTopOrigins(SelectQuery $query, int $langid): Query
    {
        
        if ($langId === null) {
            throw new \InvalidArgumentException('Language ID must be provided.');
        }


        // First Query
        $query = $this->find(
            type: 'list', 
            options: [
                'valueField' => 'origin',
                'order' => ['Origins.id' => 'ASC']
            ]
        )->matching(
            assoc: 'Languages', 
            builder: function ($q) use ($langId) {
                return $q->where(['OriginsLanguages.top' => 1, 'OriginsLanguages.language_id' => $langId]);
            }
        );

        // Second Query
        $query2 = $this->find(
            type: 'list', 
            options: [
                'valueField' => 'origin'
            ]
        )->where(['id' => 999]);

        // Combine Queries
        
        return $query->union($query2);
    }


    public function top_origins(array $options){
        $query = $this->find('list', 
                        valueField: 'origin', 
                        order: ['Origins.id' => 'ASC'])
                        //->contain(['Languages'])
                        ->matching('Languages')
                        ->where(['OriginsLanguages.top' => 1, 'OriginsLanguages.language_id' => $options['langid']]);

        $query2 = $this->find('list', valueField: 'origin')
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